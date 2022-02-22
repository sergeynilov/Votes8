<?php namespace App\library {

    use Auth;
    use DB;
    use App\User;
    use App\Settings;
    use App\Http\Traits\funcsTrait;
    use ZipArchive;  //http://php.net/manual/en/class.ziparchive.php


    class AppArchiver
    {
        use funcsTrait;


        public function archiveUserRegistrationFiles($use_session = false)
        {
//            $userRegistrationFilesArray = ['uploads/user-registration-files/rules-of-our-site.pdf', 'uploads/user-registration-files/terms.doc ', ' uploads/user-registration-files/our-services.doc'];
            $user_registration_files = Settings::getValue('userRegistrationFiles');
            $userRegistrationFilesArray= $this->pregSplit('/;/', $user_registration_files);

            $public_dir= public_path();

            $this->createDir( [ $public_dir.'/storage', $public_dir.'/storage/tmp' ] );


            $dest_zip_file_name = $public_dir . '/storage/tmp/userRegistrationDocuments.zip';
            $zip = new ZipArchive;

            //echo '<pre>$::'.print_r($,true).'</pre>';
            with(new self)->info( '<pre>-1 $dest_zip_file_name::'.print_r($dest_zip_file_name,true).'</pre>'  );
            foreach ($userRegistrationFilesArray as $next_file_path) { // all files to attach
                $next_file_path= trim($next_file_path);
//                with(new self)->info( '<pre>-2 $zip::'.print_r($zip,true).'</pre>'  );
                $filename_basename= $this->getFilenameBasename($next_file_path);
                with(new self)->info( '<pre>-30 $next_file_path::'.print_r($next_file_path,true).'</pre>'  );
                with(new self)->info( '<pre>-3 $filename_basename::'.print_r($filename_basename,true).'</pre>'  );
                $extension = \File::extension($next_file_path);

                if ($zip->open( /* $public_dir . '/' . */ $dest_zip_file_name, ZipArchive::CREATE) === true) {

                    with(new self)->info( '<pre>-4 $filename_basename.'.'.$extension::'.print_r($filename_basename.'.'.$extension,true).'</pre>'  );

                    $zip->addFile($next_file_path, $filename_basename.'.'.$extension);
                }
            } // foreach( $userRegistrationFilesArray as $next_file_path ) { // all files to attach

            $zip->close();
            return $dest_zip_file_name;
        } // public function archiveUserRegistrationFiles()

        private function folderToZip($folder, &$zipFile, $exclusiveLength)
        {
            $handle = opendir($folder);
            while (false !== $f = readdir($handle)) {
                if ($f != '.' && $f != '..') {
                    $filePath = "$folder/$f";
                    // Remove prefix from file path before add to zip.
                    $localPath = substr($filePath, $exclusiveLength);
                    if (is_file($filePath)) {
                        $zipFile->addFile($filePath, $localPath);
                    } elseif (is_dir($filePath)) {
                        // Add sub-directory.
                        $zipFile->addEmptyDir($localPath);
                        self::folderToZip($filePath, $zipFile, $exclusiveLength);
                    }
                }
            }
            closedir($handle);
        }
    }

}



/*     if($request->has('download')) {
// Define Dir Folder
$public_dir=public_path();
// Zip File Name
$zipFileName = 'AllDocuments.zip';
// Create ZipArchive Obj
$zip = new ZipArchive;
if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {
// Add File in ZipArchive
$zip->addFile(file_path,'file_name');
// Close ZipArchive
$zip->close();
}
// Set Header
$headers = array(
'Content-Type' => 'application/octet-stream',
);
$filetopath=$public_dir.'/'.$zipFileName;
// Create Download Response
if(file_exists($filetopath)){
return response()->download($filetopath,$zipFileName,$headers);
}
}
return view('createZip');
}*/

