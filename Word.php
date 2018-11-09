<?php


//word处理类
class Word{
    var $config = array();
    var $headers = array();
    var $headers_exists = array();
    var $files = array();
    var $boundary;
    var $dir_base;
    var $page_first;
 
    function MhtFile($config = array()){
 
    }
 
    function SetHeader($header){
        $this->headers[] = $header;
        $key = strtolower(substr($header, 0, strpos($header, ':')));
        $this->headers_exists[$key] = TRUE;
    }
 
    function SetFrom($from){
        $this->SetHeader("From: $from");
    }
 
    function SetSubject($subject){
        $this->SetHeader("Subject: $subject");
    }
 
    function SetDate($date = NULL, $istimestamp = FALSE){
        if ($date == NULL) {
            $date = time();
        }
        if ($istimestamp == TRUE) {
            $date = date('D, d M Y H:i:s O', $date);
        }
        $this->SetHeader("Date: $date");
    }
 
    function SetBoundary($boundary = NULL){
        if ($boundary == NULL) {
            $this->boundary = '--' . strtoupper(md5(mt_rand())) . '_MULTIPART_MIXED';
        } else {
            $this->boundary = $boundary;
        }
    }
 
    function SetBaseDir($dir){
        $this->dir_base = str_replace("\\", "/", realpath($dir));
    }
 
    function SetFirstPage($filename){
        $this->page_first = str_replace("\\", "/", realpath("{$this->dir_base}/$filename"));
    }
 
    function AutoAddFiles(){
        if (!isset($this->page_first)) {
            exit ('Not set the first page.');
        }
        $filepath = str_replace($this->dir_base, '', $this->page_first);
        $filepath = 'http://mhtfile' . $filepath;
        $this->AddFile($this->page_first, $filepath, NULL);
        $this->AddDir($this->dir_base);
    }
 
    function AddDir($dir){
        $handle_dir = opendir($dir);
        while ($filename = readdir($handle_dir)) {
            if (($filename!='.') && ($filename!='..') && ("$dir/$filename"!=$this->page_first)) {
                if (is_dir("$dir/$filename")) {
                    $this->AddDir("$dir/$filename");
                } elseif (is_file("$dir/$filename")) {
                    $filepath = str_replace($this->dir_base, '', "$dir/$filename");
                    $filepath = 'http://mhtfile' . $filepath;
                    $this->AddFile("$dir/$filename", $filepath, NULL);
                }
            }
        }
        closedir($handle_dir);
    }
 
    function AddFile($filename, $filepath = NULL, $encoding = NULL){
        if ($filepath == NULL) {
            $filepath = $filename;
        }
        $mimetype = $this->GetMimeType($filename);
        $filecont = file_get_contents($filename);
        $this->AddContents($filepath, $mimetype, $filecont, $encoding);
    }
 
    function AddContents($filepath, $mimetype, $filecont, $encoding = NULL){
        if ($encoding == NULL) {
            $filecont = chunk_split(base64_encode($filecont), 76);
            $encoding = 'base64';
        }
        $this->files[] = array('filepath' => $filepath,
                               'mimetype' => $mimetype,
                               'filecont' => $filecont,
                               'encoding' => $encoding);
    }
 
    function CheckHeaders(){
        if (!array_key_exists('date', $this->headers_exists)) {
            $this->SetDate(NULL, TRUE);
        }
        if ($this->boundary == NULL) {
            $this->SetBoundary();
        }
    }
 
    function CheckFiles(){
        if (count($this->files) == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
 
    function GetFile(){
        $this->CheckHeaders();
        if (!$this->CheckFiles()) {
            exit ('No file was added.');
        }
        $contents = implode("\r\n", $this->headers);
        $contents .= "\r\n";
        $contents .= "MIME-Version: 1.0\r\n";
        $contents .= "Content-Type: multipart/related;\r\n";
        $contents .= "\tboundary=\"{$this->boundary}\";\r\n";
        $contents .= "\ttype=\"" . $this->files[0]['mimetype'] . "\"\r\n";
        $contents .= "X-MimeOLE: Produced By Mht File Maker v1.0 beta\r\n";
        $contents .= "\r\n";
        $contents .= "This is a multi-part message in MIME format.\r\n";
        $contents .= "\r\n";
        foreach ($this->files as $file) {
            $contents .= "--{$this->boundary}\r\n";
            $contents .= "Content-Type: $file[mimetype]\r\n";
            $contents .= "Content-Transfer-Encoding: $file[encoding]\r\n";
            $contents .= "Content-Location: $file[filepath]\r\n";
            $contents .= "\r\n";
            $contents .= $file['filecont'];
            $contents .= "\r\n";
        }
        $contents .= "--{$this->boundary}--\r\n";
        return $contents;
    }
 
    function MakeFile($filename){
        $contents = $this->GetFile();
        $fp = fopen($filename, 'w');
        fwrite($fp, $contents);
        fclose($fp);
    }
 
    function GetMimeType($filename){
        $pathinfo = pathinfo($filename);
        switch ($pathinfo['extension']) {
            case 'htm': $mimetype = 'text/html'; break;
            case 'html': $mimetype = 'text/html'; break;
            case 'txt': $mimetype = 'text/plain'; break;
            case 'cgi': $mimetype = 'text/plain'; break;
            case 'php': $mimetype = 'text/plain'; break;
            case 'css': $mimetype = 'text/css'; break;
            case 'jpg': $mimetype = 'image/jpeg'; break;
            case 'jpeg': $mimetype = 'image/jpeg'; break;
            case 'jpe': $mimetype = 'image/jpeg'; break;
            case 'gif': $mimetype = 'image/gif'; break;
            case 'png': $mimetype = 'image/png'; break;
            default: $mimetype = 'application/octet-stream'; break;
        }
        return $mimetype;
    }


    /**
     * 根据HTML代码获取word文档内容
     * 创建一个本质为mht的文档，该函数会分析文件内容并从远程下载页面中的图片资源
     * 该函数会分析img标签，提取src的属性值。但是，src的属性值必须被引号包围，否则不能提取
     *
     * @param string $content HTML内容
     * @param string $absolutePath 网页的绝对路径。如果HTML内容里的图片路径为相对路径，那么就需要填写这个参数，来让该函数自动填补成绝对路径。这个参数最后需要以/结束
     * @param bool $isEraseLink 是否去掉HTML内容中的链接
     */
    public static function WordMake($content , $absolutePath = "" , $isEraseLink = true){
        $mht = new Word();
        if ($isEraseLink){
            $content = preg_replace('/<a\s*.*?\s*>(\s*.*?\s*)<\/a>/i' , '$1' , $content);   //去掉链接
        }
        $images = array();
        $files = array();
        $matches = array();
        //src后的属性值必须使用引号括起来
        if ( preg_match_all('/<img[.\n]*?src\s*?=\s*?[\"\'](.*?)[\"\'](.*?)\/>/i',$content ,$matches ) ){
            $arrPath = $matches[1];
            for ( $i=0;$i<count($arrPath);$i++) {
                $path = $arrPath[$i];
                $imgPath = trim( $path );
                if ( $imgPath != "" ) {
                    $files[] = $imgPath;
                    if( substr($imgPath,0,7) == 'http://') {
                        //绝对链接，不加前缀
                    } else {
                        $imgPath = $absolutePath.$imgPath;
                    }
                    $images[] = $imgPath;
                }
            }
        }
        $mht->AddContents("tmp.html",$mht->GetMimeType("tmp.html"),$content);
        for ( $i=0;$i<count($images);$i++) {
            $image = $images[$i];
            if ( @fopen($image , 'r') ) {
                $imgContent = @file_get_contents( $image );
                if ( $content )
                    $mht->AddContents($files[$i],$mht->GetMimeType($image),$imgContent);
            } else {
                echo "file:".$image." not exist!<br />";
            }
        }
        return $mht->GetFile();
    }

    /**
     * 根据html的body代码组合上头尾部分，生成完整的html代码
     * @param string $body	html的body代码
     * @return string   完整的html代码
     */
    public static function makeHtmlCode($body){
        //在<head>部分加上 <STYLE>BR.page { page-break-after: always }</STYLE> 可实现打印的时候分页
        $wordStr = '<html xmlns:o="urn:schemas-microsoft-com:office:office"
			xmlns:w="urn:schemas-microsoft-com:office:word"
			xmlns="http://www.w3.org/TR/REC-html40">
			<head>
			<STYLE>BR.page { page-break-after: always }</STYLE>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			</head><body>';

        $wordStr .= $body;
        $wordStr .= '</body></html>';

        return $wordStr;
    }

    /**
     * 导出成word文件
     * @param string $body	html的body代码
     * @param string $fileName	word的文件名
     * @return void
     */
    public static function exportWord($body,$fileName){

        header("Cache-Control: no-cache, must-revalidate");
        header("Pragma: no-cache");

        $wordStr = self::makeHtmlCode($body);

        $wordStr = self::WordMake($wordStr);    //生成word内容

        header("Content-Type: application/doc");
        header("Content-Disposition: attachment; filename=" . $fileName . ".doc");
        echo $wordStr;
        return true;
    }
}
