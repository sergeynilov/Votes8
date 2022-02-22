<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Auth;
use App\Http\Traits\funcsTrait;
//use App\Exceptions\NotFoundHttpException;
use Throwable;
use Session;

//use App\Exceptions\UserNotVerifiedException;
//use App\Exceptions\NotFoundHttpException;
use Illuminate\Support\Facades\Redirect;
//use Illuminate\Foundation\Auth\RegistersUsers;

//use Jrean\UserVerification\Traits\VerifiesUsers;
//use Jrean\UserVerification\Facades\UserVerification;

class Handler extends ExceptionHandler
{
    use funcsTrait;
//    use RegistersUsers;
//    use VerifiesUsers;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */

//    public function report(Exception $exception)
//    {
//        parent::report($exception);
//    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
//        echo '<pre>$exception::'.print_r($exception,true).'</pre>';
//        dump($exception);

        if ($exception instanceof NotFoundHttpException) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Not Found'], 404);
            }

            return view('40488', ['frontend_template_name'=> 'ZZZ'], 404);
            //         return view($this->getBackendTemplateName() . '.admin.reports.votes', $viewParamsArray);

        }

/*        if($exception instanceof App\Exceptions\NotFoundHttpException)
        {
            //         return view($this->getFrontendTemplateName().'.votes_by_category', $viewParamsArray);
            die("-1 XXZ render -77 INSIDE");
            return response()->view('page_not_found', [], 404);
        }*/
//        die("-1 XXZ render OUT");

        if ($exception instanceof \Jrean\UserVerification\Exceptions\UserNotVerifiedException) {
//            dump("Make Logout---");
            Auth::logout();
            $this->setFlashMessage("Your account is not verified. Check you account email for activation code !", 'danger');
            return redirect('/login');
        }

        return parent::render($request, $exception);
    }
}
