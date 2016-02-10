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
                case 'plan-created':
                    $emailSubject = $data['description'] . ' - ' . config('testplanner.mail.subjects.plan_created');
                    $emailType    = 'emails.plan_created';
                    break;

                case 'plan-updated':
                    $emailSubject = $data['description'] . ' - ' . config('testplanner.mail.subjects.plan_updated');
                    $emailType    = 'emails.plan_updated';
                    break;

                case 'ticket-response':
                    $emailSubject =  $data['description'] . ' - ' . config('testplanner.mail.subjects.ticket_response') . ' ' . $data['assignee'];
                    $emailType    = 'emails.ticket_response';
                    break;
            }

            // Type of email to be send out
            switch($emailType) {
                case 'emails.plan_created':
                case 'emails.plan_updated':
                    if (count($data['testers']) > 1) {
                        // Multiple testers
                        foreach ($data['testers'] as $tester) {

                            Mail::send($emailType, array_merge($data, $tester), function ($message) use ($tester, $emailSubject) {
                                $message->to($tester['email'], $tester['first_name'])->subject($emailSubject);
                            });
                        }
                    } else {
                        // Single tester
                        $tester = array_shift($data['testers']);

                        Mail::send($emailType, array_merge($data, $tester), function ($message) use ($tester, $emailSubject) {
                            $message->to($tester['email'], $tester['first_name'])->subject($emailSubject);
                        });
                    }
                break;

                case 'emails.ticket_response':
                    $data += [
                        'testerEmail'  => Tools::getUserEmail($data['tester_id']),
                        'creatorEmail' => Tools::getUserEmail($data['creator_id'])
                    ];

                    if ($data['creatorEmail'] != $data['testerEmail']) {
                        Mail::send($emailType, $data, function ($message) use ($data, $emailSubject) {
                            $message->from($data['testerEmail'], $data['assignee']);
                            $message->to($data['creatorEmail'], $data['reporter'])->subject($emailSubject);
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