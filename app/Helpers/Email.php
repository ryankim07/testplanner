<?php namespace App\Helpers;

/**
 * Class Email
 *
 * Helper
 *
 * @author     Ryan Kim
 * @category   Mophie
 * @package    Test Planner
 * @copyright  Copyright (c) 2016 mophie (https://lpp.nophie.com)
 */

use App\Facades\Utils;

use Mail;
use Config;

class Email
{
    /**
     * Send email
     *
     * @param $type
     * @param $data
     * @return mixed
     */
    public function sendEmail($type, $data)
    {
        // Type of email to be send out
        switch($type) {
            case 'plan-created':
                $emailSubject = $data['description'] . ': ' . config('mail.plan_created_subject');
                $emailType    = 'emails.plan_created';
            break;

            case 'ticket-response':
                $emailSubject =  $data['description'] . ': ' . config('mail.ticket_response_subject');
                $emailType    = 'emails.ticket_response';
                break;

            /*case 'creator':
                $emailSubject = config('mail.confirmation_subject');
                $emailType    = 'emails.registration_confirmation';
            break;

            case 'admin-system':
                $data['email']     = config('h2pro.admin_email');
                $data['firstname'] = config('h2pro.admin_name');
                $emailSubject      = config('mail.admin_system_subject');
                $emailType         = 'emails.admin_system';
            break;*/
        }

        // Process email
        try {
            switch($emailType) {
                case 'emails.plan_created':
                    if (count($data['testers']) > 1) {
                        // Multiple testers
                        foreach ($data['testers'] as $tester) {
                            Mail::send($emailType, array_merge($data, $tester), function ($message) use ($tester, $emailSubject) {
                                $message->to($tester['email'], $tester['first_name'])
                                    ->subject($emailSubject);
                            });
                        }
                    } else {
                        // Single tester
                        $tester = array_shift($data['testers']);
                        Mail::send($emailType, array_merge($data, $tester), function ($message) use ($tester, $emailSubject) {
                            $message->to($tester['email'], $tester['first_name'])
                                ->subject($emailSubject);
                        });
                    }
                    break;

                case 'emails.ticket_response':
                    Mail::send($emailType, $data, function ($message) use ($data, $emailSubject) {
                        $message->to($data['email'], $data['first_name'])
                            ->subject($emailSubject);
                    });
                break;
            }
        } catch(\Exception $e) {
            // Log to system
            Utils::log($e->getMessage(), $data);
        }

        return true;
    }
}