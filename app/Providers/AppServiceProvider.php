<?php

namespace App\Providers;

//use Illuminate\Support\Facades\Auth;
use Auth;
use Validator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Database\Events\TransactionCommitted;
use Illuminate\Database\Events\TransactionRolledBack;
use Illuminate\Support\Facades\Gate;
use App\Http\Traits\funcsTrait;
use App\User;
use App\VoteCategory;
use App\VoteItem;
use App\Settings;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    use funcsTrait;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        // if (  $request->isMethod('post')  ) {
//        $request= request();
        if ( !$this->isCliCommand() ) {   // It is run under web/ command line / tests
            if ( \Schema::hasTable(with( new Settings)->getTable() ) ) {   // It is run under web/ command line / tests
                //  we do not need it  command line
                $settingsArray = Settings::getSettingsList(['site_name', 'site_heading', 'noreply_email'], -1, __FILE__, __LINE__);
                $site_name     = ! empty($settingsArray['site_name']) ? $settingsArray['site_name'] : '';
                $site_heading  = ! empty($settingsArray['site_heading']) ? $settingsArray['site_heading'] : '';
                $noreply_email = ! empty($settingsArray['noreply_email']) ? $settingsArray['noreply_email'] : '';

                config(['feed.feeds.main.title' => htmlspecialchars_decode($site_name . ' : ' . $site_heading)]);
                config(['mail.from.name' => htmlspecialchars_decode($site_name . ' support')]);
                config(['mail.from.address' => htmlspecialchars_decode($noreply_email)]);
            }
        }
//        if( !$this->isDeveloperComp() ) {
//            \URL::forceScheme('https');
//        }

        if( $this->isHttpsProtocol() ) {
            \URL::forceScheme('https');
        }


        if ($this->app->environment('local')) {


            \Event::listen(
                [
                    TransactionBeginning::class,
                ],
                function ($event) {
                    //return; // to comment

                    $str   = "  BEGIN; ";
                    $dbLog = new \Monolog\Logger('Query');
                    if ($this->isDeveloperComp()) {
                        $dbLog->pushHandler(new \Monolog\Handler\RotatingFileHandler(storage_path('logs/Query.txt'), 5, \Monolog\Logger::DEBUG));
                        $dbLog->info($str);
                        $dbLog->info('');
                        $dbLog->info('');
                    }
                    writeSqlToLog($str, '', true);
                    writeSqlToLog("");
                    writeSqlToLog("");
                });


            \Event::listen(
                [
                    TransactionCommitted::class,
                ],
                function ($event) {
                    //return; // to comment

                    $str   = "  COMMIT; ";
                    $dbLog = new \Monolog\Logger('Query');
                    if ($this->isDeveloperComp()) {
                        $dbLog->pushHandler(new \Monolog\Handler\RotatingFileHandler(storage_path('logs/Query.txt'), 5, \Monolog\Logger::DEBUG));
                        $dbLog->info($str);
                        $dbLog->info('');
                        $dbLog->info('');
                    }
                    writeSqlToLog($str, '', true);
                    writeSqlToLog("");
                    writeSqlToLog("");
                });


            \Event::listen(
                [
                    TransactionRolledBack::class,
                ],
                function ($event) {
                    //return; // to comment
                    //
                    $str   = "  ROLLBACK; ";
                    $dbLog = new \Monolog\Logger('Query');
                    if ($this->isDeveloperComp()) {
                        $dbLog->pushHandler(new \Monolog\Handler\RotatingFileHandler(storage_path('logs/Query.txt'), 5, \Monolog\Logger::DEBUG));
                        $dbLog->info($str);
                        $dbLog->info('');
                        $dbLog->info('');
                    }
                    writeSqlToLog($str, '', true);
                    writeSqlToLog("");
                    writeSqlToLog("");
                });


            \DB::listen(function ($query) {
                // return; // to comment

                $dbLog = new \Monolog\Logger('Query');
                if ($this->isDeveloperComp()) {
                    $dbLog->pushHandler(new \Monolog\Handler\RotatingFileHandler(storage_path('logs/Query.txt'), 5, \Monolog\Logger::DEBUG));
                }
                $str = $query->sql;
                $str = str_replace('%', 'QWERTYQWERTY', $str);
                $str = str_replace('?', "%s", $str);
                if (count($query->bindings) == 1) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'");
                }
                if (count($query->bindings) == 2) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'");
                }
                if (count($query->bindings) == 3) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'");
                }
                if (count($query->bindings) == 4) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'");
                }
                if (count($query->bindings) == 5) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'");
                }
                if (count($query->bindings) == 6) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'");
                }


                if (count($query->bindings) == 7) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'");
                }

                if (count($query->bindings) == 8) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'");
                }

                if (count($query->bindings) == 9) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'");
                }

                if (count($query->bindings) == 10) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'");
                }

                if (count($query->bindings) == 11) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'");
                }

                if (count($query->bindings) == 12) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'");
                }


                if (count($query->bindings) == 13) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'");
                }


                if (count($query->bindings) == 14) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'", "'" . $query->bindings[13] . "'");
                }


                if (count($query->bindings) == 15) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'", "'" . $query->bindings[13] . "'", "'" . $query->bindings[14] . "'");
                }


                if (count($query->bindings) == 16) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'", "'" . $query->bindings[13] . "'", "'" . $query->bindings[14] . "'", "'" . $query->bindings[15] . "'");
                }


                if (count($query->bindings) == 17) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'", "'" . $query->bindings[13] . "'", "'" . $query->bindings[14] . "'", "'" . $query->bindings[15] . "'",
                        "'" . $query->bindings[16] . "'");
                }

                if (count($query->bindings) == 18) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'", "'" . $query->bindings[13] . "'", "'" . $query->bindings[14] . "'", "'" . $query->bindings[15] . "'",
                        "'" . $query->bindings[16] . "'", "'" . $query->bindings[17] . "'");
                }

                if (count($query->bindings) == 19) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'", "'" . $query->bindings[13] . "'", "'" . $query->bindings[14] . "'", "'" . $query->bindings[15] . "'",
                        "'" . $query->bindings[16] . "'", "'" . $query->bindings[17] . "'", "'" . $query->bindings[18] . "'");
                }


                if (count($query->bindings) == 20) {
                    $str = sprintf($str, "'" . $query->bindings[0] . "'", "'" . $query->bindings[1] . "'", "'" . $query->bindings[2] . "'", "'" . $query->bindings[3] . "'",
                        "'" . $query->bindings[4] . "'", "'" . $query->bindings[5] . "'", "'" . $query->bindings[6] . "'", "'" . $query->bindings[7] . "'",
                        "'" . $query->bindings[8] . "'", "'" . $query->bindings[9] . "'", "'" . $query->bindings[10] . "'", "'" . $query->bindings[11] . "'",
                        "'" . $query->bindings[12] . "'", "'" . $query->bindings[13] . "'", "'" . $query->bindings[14] . "'", "'" . $query->bindings[15] . "'",
                        "'" . $query->bindings[16] . "'", "'" . $query->bindings[17] . "'", "'" . $query->bindings[18] . "'", "'" . $query->bindings[19] . "'");
                }


                $str = str_replace('QWERTYQWERTY', '%', $str);

                writeSqlToLog($str, 'Time ' . $query->time . ' : ' . PHP_EOL);
                writeSqlToLog('');
                writeSqlToLog('');
//                $dbLog->info($str, ['Time' => $query->time]);
//                $dbLog->info('');
//                $dbLog->info('');

            }); // \DB::listen(function($query) {


        } // if ($this->app->environment('local')) {


        /* check is entered name is valid */
        Validator::extend('check_nice_name', function ($user, $value, $parameters, $validator) {
            $valid_nice_name_format = config('app.valid_nice_name_format');
            $ret                    = preg_match($valid_nice_name_format . 'si', $value, $a);

            return $ret;
        });

        Validator::extend('check_vote_item_unique_by_name', function ($attribute, $value, $parameters, $validator) {
            $vote_id         = $parameters[0] ?? null;
            $vote_item_id    = $parameters[1] ?? null;
            $vote_item_count = VoteItem::getSimilarVoteItemByName($value, (int)$vote_id, (int)$vote_item_id, true);

            return $vote_item_count == 0;
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local')) {
//            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
//            $this->app->register(TelescopeServiceProvider::class);
        }
        /*$this->app->bind(ElastTTTic::class, function ($app) {
            return new Elastic(
                ClientBuilder::create()
                             ->setLogger(ClientBuilder::defaultLogger(storage_path('logs/elastic.log')))
                             ->build()
            );
        }); */

    }
}


function formatSql($sql, $is_break_line = true, $is_include_html = true)
{
//    return $sql;
    $space_char = '  ';

    $bold_start = '';
    $bold_end   = '';
    $break_line = PHP_EOL;

    $sql        = ' ' . $sql . ' ';
    $left_cond  = '~\b(?<![%\'])';
    $right_cond = '(?![%\'])\b~i';
    $sql        = preg_replace($left_cond . "insert[\s]+into" . $right_cond, $space_char . $space_char . $bold_start . "INSERT INTO" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "insert" . $right_cond, $space_char . $bold_start . "INSERT" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "delete" . $right_cond, $space_char . $bold_start . "DELETE" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "values" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "VALUES" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "update" . $right_cond, $space_char . $bold_start . "UPDATE" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "inner[\s]+join" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "INNER JOIN" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "straight[\s]+join" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "STRAIGHT_JOIN" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "left[\s]+join" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "LEFT JOIN" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "select" . $right_cond, $space_char . $bold_start . "SELECT" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "from" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "FROM" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "where" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "WHERE" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "group by" . $right_cond, $break_line . $space_char . $space_char . "GROUP BY" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "having" . $right_cond, $break_line . $space_char . $bold_start . "HAVING" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "order[\s]+by" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "ORDER BY" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "and" . $right_cond, $space_char . $space_char . $bold_start . "AND" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "or" . $right_cond, $space_char . $space_char . $bold_start . "OR" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "as" . $right_cond, $space_char . $space_char . $bold_start . "AS" . $bold_end, $sql);
    $sql        = preg_replace($left_cond . "exists" . $right_cond, $break_line . $space_char . $space_char . $bold_start . "EXISTS" . $bold_end, $sql);

    return $sql;
}

function writeSqlToLog($contents, string $descr_text = '', bool $is_sql = false, string $file_name = '')
{
    $debug = config('app.debug');
    if (!$debug) return;

    $url = config('app.url');

//    if (!$this->isDeveloperComp()) return;

    $pos = strpos($url, 'local-votes.com');
    if ( !($pos === false)) {
//        return;
    }

    if (empty($descr_text)) {
        $descr_text = '';
    }
    try {
        if (empty($file_name)) {
            $file_name = storage_path() . '/logs/sql-tracing-' /*. (strftime("%y-%m-%d"))*/ . '.txt';
        }
//        echo '<pre>$file_name::'.print_r($file_name,true).'</pre>';
//                $fd = fopen($file_name, ($is_clear_text ? "w+" : "a+"));
        $fd = fopen($file_name, "a+");

//        echo '<pre>$contents::'.print_r($contents,true).'</pre>';
        if (is_array($contents)) {
            $contents = print_r($contents, true);
        }
        $is_sql = 1;
        if ($is_sql) {
            fwrite($fd, $descr_text . formatSql($contents, true) . chr(13));
        } else {
            fwrite($fd, $descr_text . $contents . chr(13));
        }
        //                     echo '<b><I>' . gettype($Var) . "</I>" . '&nbsp;$Var:=<font color= red>&nbsp;' . AppUtil::showFormattedSql($Var) . "</font></b></h5>";
//        echo '<pre>++$contents::'.print_r($contents,true).'</pre>';

        fclose($fd);

//        die("-1 XXZ writeSqlToLog");
        return true;
    } catch (Exception $lException) {
        return false;
    }
}

