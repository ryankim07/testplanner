<?php namespace App\Api;

/**
 * Class EmailApi
 *
 * Helper
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://tp.nophie.us)
 */

use App\Facades\Tools;

use Mail;
use Session;

class EmailApi
{
    /**
     * Send email
     *
     * @param $type
     * @param $data
     * @return mixed
     */
    public static function sendEmail($type, $data)
    {
        try {
            // Subject and email type selector
            switch($type) {
                case 'plan_created':
                case 'plan_updated':
                    $emailSubject = $data['description'] . ' - ' . config('testplanner.mail.subjects.' . $type);

                    foreach ($data['testers'] as $tester) {
                        $type = 'emails.' . $type;
                        $tester['browsers'] = Tools::translateBrowserName($tester['browsers']);

                        if ($type = 'plan_updated' && $tester['update_status'] != 0) {
                            $type = 'emails.plan_browser_updated';
                        }

                        Mail::send($type, array_merge($data, $tester), function ($message) use ($tester, $emailSubject) {
                            $message->to($tester['email'], $tester['first_name'])->subject($emailSubject);
                        });
                    }
                break;

                case 'ticket_response':
                    $emailSubject =  $data['description'] . ' - ' . config('testplanner.mail.subjects.' . $type) . ' ' . $data['assignee'];

                    $data += [
                        'tester_email'  => Tools::getUserEmail($data['tester_id']),
                        'creator_email' => Tools::getUserEmail($data['creator_id'])
                    ];

                    if ($data['creator_email'] != $data['tester_email']) {
                        Mail::send('emails.' . $type, $data, function ($message) use ($data, $emailSubject) {
                            $message->from($data['tester_email'], $data['assignee']);
                            $message->to($data['creator_email'], $data['reporter'])->subject($emailSubject);
                        });
                    }
                break;
            }
        } catch(\Exception $e) {
            Tools::log($e->getMessage() . ' email sending', $data);
            Session::flash('flash_error', config('testplanner.messages.plan.system.email_error'));
        }

        return true;
    }
}