<?php

namespace App\Events;

use App\Models\JobDraft;
use App\Models\Notification;
use App\Models\Request as ModelsRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

use Illuminate\Queue\SerializesModels;
use Pusher\Pusher;
use Symfony\Component\HttpFoundation\Request;

class PushNotificationEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $month;
    public $year;
    public $from_user_id;
    public $to_user_id;

    public $request;

    protected $pusher;

    protected $role;

    /**
     * Create a new event instance.
     */
    public function __construct(Request $request, $type = null)
    {

        $this->request = $request;

        $this->pusher = new Pusher(
            env("PUSHER_APP_KEY"),
            env("PUSHER_APP_SECRET"),
            env("PUSHER_APP_ID"),
            ["cluster" => env("PUSHER_APP_CLUSTER"), "useTLS" => true]
        );

        switch ($type) {
            case 'send-download-approval-dtr':
                $this->month = $request->month;
                $this->year = $request->year;
                $this->message = $request->message;
                $this->from_user_id = $request->from_user_id;
                $this->role = $request->role;

                $this->sendDtrApprovalPushNotification();
                break;
            case 'send-response-download-dtr':
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendDtrResponsePushNotification();
                break;
            case 'send-chat-message':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendChatMessagePushNotification();
                break;
            case 'send-job-order-created-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendCreateJobOrderPushNotification();
                break;
            case 'send-job-order-accepted-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendAcceptedJobOrderPushNotification();
                break;
            case 'send-job-order-rejected-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendRejectedJobOrderPushNotification();
                break;
            case 'send-job-order-approved-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendApprovedJobOrderPushNotification();
                break;
            case 'send-job-order-renewal-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendRenewalJobOrderPushNotification();
                break;
            case 'send-job-order-task-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendTaskJobOrderPushNotification();
                break;
            case 'send-job-order-revise-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendReviseJobOrderPushNotification();
                break;
            case 'send-job-order-request-notification':
                $this->from_user_id = $request->from_user_id;
                $this->to_user_id = $request->to_user_id;
                $this->message = $request->message;

                $this->sendRequestJobOrderPushNotification();
                break;
        }
    }

    /**
     * Manually trigger Pusher.
     */

    public function sendChatMessagePushNotification() {}
    public function sendDtrResponsePushNotification()
    {
        $this->pusher->trigger("public-notifications", "user-notification-{$this->to_user_id}", [
            'message' => $this->message,
            'success' => true,
        ]);
    }
    public function sendDtrApprovalPushNotification()
    {
        //if the user has role admin and is more than 1 use forloop
        $users = User::whereIn('role', $this->request->role)->get();

        if (isset($user) || $users != null) {
            foreach ($users as $user) {
                // ✅ Send event to a PUBLIC CHANNEL
                $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                    'message' => $this->message,
                    'success' => true,
                ]);
            }
        }

        // // Send only to a private channel for user 1
        // $pusher->trigger('private-notifications.' . $this->user_id, 'form-submitted', $data);
    }

    public function sendCreateJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A job order has been assigned to you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Job Order has been created!',
                        'success' => true,
                    ]);
                }
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function sendRequestJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Project request has been assigned by you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A Project Request has been created!',
                        'success' => true,
                    ]);
                }
            }

            $request = ModelsRequest::where('id', $this->request->job_order_id);

            if (isset($request)) {
                $request = $request->first();

                $fullNameFormatted = User::where('id', $request->issued_by)->first()->name;

                $notification = Notification::create([
                    'user_id' => $request->assigned_to,
                    'from_user_id' => $request->issed_by,
                    'title' => $request->title,
                    'message' => $fullNameFormatted . '. has requested you to create a job order',
                    'is_read' => false,
                    'is_archive' => false,
                    'type' => 'admin.smm.request.job-order',
                ]);

                $this->pusher->trigger("public-notifications", "user-notification-{$notification->user_id}", [
                    'message' => 'A Project Request has been requested to you!',
                    'success' => true,
                ]);

                $fullNameFormatted = User::where('id', $request->assigned_to)->first()->name;

                $notification = Notification::create([
                    'user_id' => $request->issued_by,
                    'from_user_id' => $request->assigned_to,
                    'title' => $request->title,
                    'message' => 'You requested ' . $fullNameFormatted  . ' to create a job order',
                    'is_read' => false,
                    'is_archive' => false,
                    'type' => 'admin.smm.request.job-order',
                ]);
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function sendAcceptedJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A job order has been accepted by you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Job Order has been accepted!',
                        'success' => true,
                    ]);
                }
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function sendApprovedJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A job order has been approved by you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            if ($user->role == 'top_management') {
                // Send to client notification
                // ✅ Send event to a PUBLIC CHANNEL
                $client_id = JobDraft::where('job_order_id', $this->request->job_order_id)->first()->client_id;

                Notification::create([
                    'user_id' => $client_id,
                    'from_user_id' => $user->id,
                    'title' => $this->request->title,
                    'message' => 'The project you requested has been completed. You may now proceed to showcase the project.',
                    'is_read' => false,
                    'is_archive' => false,
                    'type' => $this->request->type,
                ]);

                $this->pusher->trigger("public-notifications", "user-notification-{$client_id}", [
                    'message' => 'The project you requested has been completed.',
                    'success' => true,
                ]);

                echo "Notification successfully sent to user ID: {$user->id} <br>";

                // ✅ Send event to a PUBLIC CHANNEL
                $jobDraft = JobDraft::where('job_order_id', $this->request->job_order_id)->first();

                // Extract the content_writer_id and graphic_designer_id once
                $content_writer_id = $jobDraft->content_writer_id ?? null;
                $graphic_designer_id = $jobDraft->graphic_designer_id ?? null;

                // Prepare an array of roles to notify (both content writer and graphic designer)
                $recipients = [
                    'content_writer' => $content_writer_id,
                    'graphic_designer' => $graphic_designer_id
                ];

                // Loop through each role and send notifications
                foreach ($recipients as $role => $recipient_id) {
                    if ($recipient_id) {
                        // Create the notification for the recipient
                        Notification::create([
                            'user_id' => $recipient_id,
                            'from_user_id' => $user->id,
                            'title' => $this->request->title,
                            'message' => 'Job order "' . $this->request->title . '" has been approved by ' . $user->name . '.',
                            'is_read' => false,
                            'is_archive' => false,
                            'type' => $this->request->type,
                        ]);

                        // Send specific messages based on the role
                        if ($role == 'graphic_designer') {
                            $this->pusher->trigger("public-notifications", "user-notification-{$recipient_id}", [
                                'message' => 'The task you submitted has been approved "' . $this->request->title . '".',
                                'success' => true,
                            ]);
                        } elseif ($role == 'content_writer') {
                            $this->pusher->trigger("public-notifications", "user-notification-{$recipient_id}", [
                                'message' => 'The project been approved.',
                                'success' => true,
                            ]);
                        }

                        echo "Notification successfully sent to user ID: {$recipient_id} <br>";
                    }
                }
            }

            if ($user->role == 'operations_supervisor') {
                // Send to client notification
                // ✅ Send event to a PUBLIC CHANNEL
                $jobDraft = JobDraft::where('job_order_id', $this->request->job_order_id)->first();

                // Extract the content_writer_id and graphic_designer_id once
                $content_writer_id = $jobDraft->content_writer_id ?? null;
                $graphic_designer_id = $jobDraft->graphic_designer_id ?? null;

                // Prepare an array of roles to notify (both content writer and graphic designer)
                $recipients = [
                    'content_writer' => $content_writer_id,
                    'graphic_designer' => $graphic_designer_id
                ];

                // Loop through each role and send notifications
                foreach ($recipients as $role => $recipient_id) {
                    if ($recipient_id) {
                        // Create the notification for the recipient
                        Notification::create([
                            'user_id' => $recipient_id,
                            'from_user_id' => $user->id,
                            'title' => $this->request->title,
                            'message' => 'Job order "' . $this->request->title . '" has been approved by ' . $user->name . '.',
                            'is_read' => false,
                            'is_archive' => false,
                            'type' => $this->request->type,
                        ]);

                        // Send specific messages based on the role
                        if ($role == 'graphic_designer') {
                            $this->pusher->trigger("public-notifications", "user-notification-{$recipient_id}", [
                                'message' => 'You are now able to proceed to work on the job order titled "' . $this->request->title . '".',
                                'success' => true,
                            ]);
                        } elseif ($role == 'content_writer') {
                            $this->pusher->trigger("public-notifications", "user-notification-{$recipient_id}", [
                                'message' => 'The task you submitted has been approved.',
                                'success' => true,
                            ]);
                        }

                        echo "Notification successfully sent to user ID: {$recipient_id} <br>";
                    }
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Job Order has been approved!',
                        'success' => true,
                    ]);
                }
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function sendRejectedJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A job order has been rejected by you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Job Order has been rejected!',
                        'success' => true,
                    ]);
                }
            }

            // Send to client notification
            // ✅ Send event to a PUBLIC CHANNEL
            $jobDraft = JobDraft::where('job_order_id', $this->request->job_order_id)->first();

            // Extract the content_writer_id and graphic_designer_id once
            $content_writer_id = $jobDraft->content_writer_id ?? null;
            $graphic_designer_id = $jobDraft->graphic_designer_id ?? null;

            // Prepare an array of roles to notify (both content writer and graphic designer)
            $recipients = [
                'content_writer' => $content_writer_id,
                'graphic_designer' => $graphic_designer_id
            ];

            // Loop through each role and send notifications
            foreach ($recipients as $role => $recipient_id) {
                if ($recipient_id) {
                    // Create the notification for the recipient
                    Notification::create([
                        'user_id' => $recipient_id,
                        'from_user_id' => $user->id,
                        'title' => $this->request->title,
                        'message' => 'Job order "' . $this->request->title . '" has been rejected by ' . $user->name . '.',
                        'is_read' => false,
                        'is_archive' => false,
                        'type' => $this->request->type,
                    ]);

                    // Send specific messages based on the role
                    if ($role == 'graphic_designer') {
                        $this->pusher->trigger("public-notifications", "user-notification-{$recipient_id}", [
                            'message' => 'Job Order has been rejected! "' . $this->request->title . '".',
                            'success' => true,
                        ]);
                    } elseif ($role == 'content_writer') {
                        $this->pusher->trigger("public-notifications", "user-notification-{$recipient_id}", [
                            'message' => 'Job Order has been rejected!',
                            'success' => true,
                        ]);
                    }

                    echo "Notification successfully sent to user ID: {$recipient_id} <br>";
                }
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function sendTaskJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A job order has been submmitted by you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Job Order has been submitted!',
                        'success' => true,
                    ]);
                }
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function sendReviseJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A job order revision has been submmitted by you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Job Order Revision has been submitted!',
                        'success' => true,
                    ]);
                }
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function sendRenewalJobOrderPushNotification()
    {
        // Check if the 'to_user_id' is set and not empty
        if (isset($this->request->to_user_id) && !empty($this->request->to_user_id)) {
            // Loop through each user ID in 'to_user_id'
            foreach ($this->request->to_user_id as $userId) {
                // Find the user based on the provided user ID
                $user = User::find($userId);

                // Ensure the user exists before sending the notification
                if ($user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'A job order has been renewed by you.',
                        'success' => true,
                    ]);
                    echo "Notification successfully sent to user ID: {$user->id} <br>";
                } else {
                    echo "User not found for ID: {$userId} <br>";
                }
            }

            //if the user has role admin and is more than 1 use forloop
            $users = User::whereIn('role', $this->request->to_user_role)->get();

            if (isset($user) || $users != null) {
                foreach ($users as $user) {
                    // ✅ Send event to a PUBLIC CHANNEL
                    $this->pusher->trigger("public-notifications", "user-notification-{$user->id}", [
                        'message' => 'Job Order has been renewed!',
                        'success' => true,
                    ]);
                }
            }
        } else {
            echo "No users to notify <br>";
        }
    }

    public function broadCastOn()
    {
        return new PrivateChannel('private-notifications.' . $this->user_id);
    }

    public function broadCastAs()
    {
        return 'chat-message';
    }

    public function broadCastWith()
    {
        return [
            'message' => $this->message,
        ];
    }
}
