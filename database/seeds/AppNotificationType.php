<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AppNotificationType extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $notificationTypes = array(
            0 => array(
                'name'   => 'Like Your Challenge',
                'class'  => 'Challenge Like'
            ),
            1 => array(
                'name'   => 'Like Your Match',
                'class'  => 'Match Like'
            ),
            2 => array(
                'name'   => 'Invite You For Challenge',
                'class'  => 'Challenge Invitation'
            ),
            3 => array(
                'name'   => 'Comment On Your Challenge',
                'class'  => 'Challenge Comment'
            ),
            4 => array(
                'name'   => 'Comment On Your Match',
                'class'  => 'Match Comment'
            ),
            5 => array(
                'name'   => 'Accept Your Challenge',
                'class'  => 'Challenge Accepted'
            ),
            6 => array(
                'name'   => 'Reject Your Challenge',
                'class'  => 'Challenge Rejected'
            ),
            7 => array(
                'name'   => 'Start Chat',
                'class'  => 'Chat Request'
            ),
            8 => array(
                'name'   => 'Admin Send You Notification',
                'class'  => 'Admin Notification'
            ),
        );

        foreach($notificationTypes as $notificationType)
        {
            DB::table('app_notification_types')->updateOrInsert([
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
                ],[
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
                ],[
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
                ],[
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
                ],[
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
                ],[
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
                ],[
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
                ],[
                    'name'   => $notificationType['name'],
                    'class'  => $notificationType['class']
            ],[
                'name'   => $notificationType['name'],
                'class'  => $notificationType['class']
        ]);
        }
    }
}
