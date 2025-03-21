<?php

namespace App\Http\Controllers;

use App\Events\PushNotificationEvent;
use App\Models\DtrDownloadRequest;
use App\Models\Notification;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Pusher\Pusher;
use Illuminate\Support\Str;

class NotificationController extends Controller
{
    // public function sendNotification(Request $request)
    // {
    //     $userId = $request->user_id;
    //     $title = "New Notification!";
    //     $body = "You have a new message.";

    //     event(new PushNotificationEvent($title, $body, $userId));

    //     return response()->json(['message' => 'Notification sent to user ' . $userId]);
    // }

    public function sendAdminNotification(Request $request)
    {

        $from_user_id = Auth::id();

        $Fullname = User::where('id', $from_user_id)->first();

        $dateMessage = Carbon::createFromDate($request->year, $request->month, 1)->format('M Y');

        $title = null;

        $fullNameFormatted = ucwords(strtolower($Fullname->firstname . ' ' . substr($Fullname->middlename, 0, 1) . '. ' . $Fullname->lastname));

        if (Str::is('user.dtr.*', $request->type)) {
            $title = $fullNameFormatted . ' has requested to download the DTR. ' . $dateMessage;
        }

        if (!empty($request->to_user_id)) {
            $userTypes = ['content', 'graphic'];

            foreach ($userTypes as $userType) {
                if (isset($request->to_user_id[$userType])) {
                    $userIds = is_array($request->to_user_id[$userType]) ? $request->to_user_id[$userType] : [$request->to_user_id[$userType]];

                    foreach ($userIds as $userId) {
                        $usr = User::find($userId);

                        if ($usr) {
                            $customTitle = $this->generateCustomNotificationTitle($request, $fullNameFormatted, $dateMessage, $userType);

                            if ($customTitle) {
                                Notification::create([
                                    'user_id' => $usr->id,
                                    'from_user_id' => $from_user_id,
                                    'title' => $request->title,
                                    'message' => $customTitle,
                                    'is_read' => false,
                                    'is_archive' => false,
                                    'type' => $request->type,
                                ]);

                                Log::info("Notification created for user ID: " . $usr->id . " (" . $userType . ")");
                            } else {
                                Log::warning("Failed to generate custom title for user ID: " . $usr->id . " (" . $userType . ")");
                            }
                        } else {
                            Log::warning("User not found for " . $userType . " with ID: " . $userId);
                        }
                    }
                }
            }

            // Notifications for roles
            $roles = ['admin', 'supervisor', 'top_manager', 'operations', 'assistant_supervisor'];
            $users = User::whereIn('role', $roles)->get();

            foreach ($users as $usr) {
                $customTitle = $this->generateCustomNotificationTitle($request, $fullNameFormatted, $dateMessage);

                if ($customTitle) {
                    Notification::create([
                        'user_id' => $usr->id,
                        'from_user_id' => $from_user_id,
                        'title' => $request->title,
                        'message' => $customTitle,
                        'is_read' => false,
                        'is_archive' => false,
                        'type' => $request->type,
                    ]);

                    Log::info("Notification created for role based user ID: " . $usr->id);
                } else {
                    Log::warning("Failed to generate custom title for role based user ID: " . $usr->id);
                }
            }

            $request->merge([
                'to_user_role' => $roles,
            ]);
        }

        // Define the mapping of request types to event names
        $eventMap = [
            'user.dtr.download.request' => 'send-download-approval-dtr',
            'admin.smm.create.job-order' => 'send-job-order-created-notification',
            'admin.smm.accept.job-order' => 'send-job-order-accepted-notification',
            'admin.smm.rejected.job-order' => 'send-job-order-rejected-notification',
            'admin.smm.renewal.job-order' => 'send-job-order-renewal-notification',
            'admin.smm.task.job-order' => 'send-job-order-task-notification',
            'admin.smm.approved.job-order' => 'send-job-order-approved-notification',
            'admin.smm.revise.job-order' => 'send-job-order-revise-notification',
            'admin.smm.request.job-order' => 'send-job-order-request-notification',
            // Add more request types and corresponding events as needed
        ];

        if (Str::is('user.dtr.download.request', $request->type)) {
            $users_role = User::whereIn('role', $request->to_user_role)->get(); // Fix: Use whereIn()

            if ($users_role->isNotEmpty()) { // Fix: Use isNotEmpty() instead of isset()
                //send to the supervisor, admin, operations, and top manager
                foreach ($users_role as $usr) {
                    Notification::create([
                        'user_id' => $usr->id,
                        'from_user_id' => $from_user_id,
                        'title' => $request->title,
                        'message' => $title,
                        'is_read' => false,
                        'is_archive' => false,
                        'type' => $request->type,
                    ]);
                }
            }

            DtrDownloadRequest::create([
                'user_id' => $from_user_id,
                'month' => $request->month,
                'year' => $request->year,
            ]);
        }

        $data = [
            "from_user_id" => $from_user_id,
            "to_user_id" => $request->id,
            "month" => $request->month,
            "year" => $request->year,
            "message" => $title,
            'role' => $request->to_user_role,
            'title' => $request->title,
            'type' => $request->type,
        ];


        // Check if the request type exists in the map
        if (isset($eventMap[$request->type])) {
            // Dispatch the corresponding event
            if (isset($eventMap[$request->type]) && $request->type == 'user.dtr.download.request') {
                $request = new Request($data);
            }
            event(new PushNotificationEvent($request, $eventMap[$request->type]));
        } else {
            // Default action if the request type is not found in the map
            event(new PushNotificationEvent($request, 'default-event'));
        }

        return response()->json(['message' => 'success!', 'success' => true]);
    }

    private function generateCustomNotificationTitle(Request $request, string $fullNameFormatted, string $dateMessage, ?string $userType = null): ?string
    {
        $customTitle = null;

        switch ($request->type) {
            case 'admin.smm.create.job-order':
                $customTitle = $fullNameFormatted . ' has created a new job order with the title: "' . $request->title . '". The context: ' . $request->message . '.';
                break;
            case 'admin.smm.approved.job-order':
                $customTitle = 'Job order "' . $request->title . '" has been approved by ' . $fullNameFormatted . '. ';
                break;
            case 'admin.smm.decline.job-order':
                $customTitle = 'Job order "' . $request->title . '" has been declined by ' . $fullNameFormatted . '. Reason: ' . $request->reason;
                break;
            case 'admin.smm.accept.job-order':
                $customTitle = 'Job order "' . $request->title . '" has been accepted by ' . $fullNameFormatted . '. Reason: ' . $request->reason;
                break;
            case 'admin.smm.renewal.job-order':
                $customTitle = 'Job order "' . $request->title . '" has been renewed by ' . $fullNameFormatted . '. Reason: ' . $request->reason;
                break;
            case 'admin.smm.task.job-order':
                $customTitle = 'The job order titled "' . $request->title . '" has been submitted by ' . $fullNameFormatted . '. Reason: ' . $request->reason . '.';
                break;
            case 'admin.smm.revise.job-order':
                $customTitle = 'The job order titled "' . $request->title . '" has been revised and submitted by ' . $fullNameFormatted . '. Reason for revision: ' . $request->reason . '.';
                break;
            case 'admin.smm.request.job-order': //all for the higher ups
                $customTitle = $fullNameFormatted . '. has requested to create a job order';
                break;
            default:
                Log::warning("Unknown notification type: " . $request->type);
                break;
        }

        return $customTitle;
    }

    public function receiveNotificationIndex()
    {
        $notificationIndex = Notification::where('user_id', Auth::id())->get();

        //return view('receive.notification', compact('notificationIndex'));
        return view('receive.notification', [
            'notificationIndex' => $notificationIndex,
        ]);
    }

    public function readUserNotification()
    {
        $notifications = Notification::where('user_id', Auth::id())->get();

        foreach ($notifications as $notification) {
            $notification->is_read = true;
            $notification->save(); // Save each notification update
        }

        //return view('receive.notification', compact('notificationIndex'));
        return view('admin-test-notification-page', [
            'notificationIndex' => $notifications,
        ]);
    }

    public function readAdminNotification($id)
    {
        $notification = Notification::where('id', $id)->first();

        $notification->is_read = true;
        $notification->save(); // Save each notification update

        // $dtrRequestIndex = DtrDownloadRequest::get();

        return response()->json(['success' => true, 'message' => 'The message has been mark as read!']);

        // //return view('receive.notification', compact('notificationIndex'));
        // return view('notifications', [
        //     'notificationIndex' => $notifications,
        //     'dtrRequestIndex' => $dtrRequestIndex,
        // ]);
    }

    public function archiveAdminNotification($id)
    {
        DB::beginTransaction();

        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notification not found'], 404);
            }

            $notification->is_archive = 1;
            $notification->save();

            DB::commit();
            return back()->with(['success' => 'The message has been archived!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
