<?php namespace App\library {

    use Auth;
    use DB;
    use App\User;
    use App\Settings;
    use App\Http\Traits\funcsTrait;
    use Spipu\Html2Pdf\Html2Pdf;


    class App2Pdf
    {
        use funcsTrait;


        public function generateProfilePage()
        {

            $html2pdf = new Html2Pdf();
            $html2pdf->writeHTML('<h1>HelloWorld</h1>This is my first test');
//            $html2pdf->output();

            $pdf= $html2pdf->Output('', 'S'); // The filename is ignored when you use 'S' as the second parameter.
            echo '<pre>$pdf::'.print_r($pdf,true).'</pre>';
            return response($pdf)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Length', strlen($pdf))
                ->header('Content-Disposition', 'inline; filename="example.pdf"');



        } // public function archiveUserRegistrationFiles()

    }

}

