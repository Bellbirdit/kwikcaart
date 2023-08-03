<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification as Notify;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function notificationManage()
    {

        $html = "";
        $user = auth()->user();
        $userNotifications = array();
        $unread = 0;
        $notificationss = $user->notifications
            ->where('read_at', '=', NULL)
            ->take(5);
        $countHead = "";
        if (isset($notificationss) && sizeof($notificationss) > 0) {
            $message = "";
            foreach ($notificationss as $key => $notification) {
                if ($key >= 10) {
                    continue;
                }
                $unread++;
                if ($notification->type == "App\Notifications\OrderNotification") {
                    $headerNote = "New Order placed";
                } elseif ($notification->type == "App\Notifications\TicketNotification") {
                    $headerNote = "New Ticket Received";
                }
                $message .= '
                            <a class="d-flex p-3 border-bottom" href="/markAsRead/' . $notification->id . '/' . $notification->data['id'] . '">
                                <div class="notifyimg bg-pink">
                                    <i class="fas fa-bell text-white"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="notification-label mb-1 noti-details notification_head">' . $headerNote . '</h5>
                                    <div class="notification-subtext noti-time notification-time">' . $notification->created_at->diffforhumans() . '</div>
                                </div>
                                <div class="ms-auto" >
                                    <i class="las la-angle-right text-end text-muted"></i>
                                </div>
                            </a>
                                ';
                $head = '<h5 class="m-0"> Notifications (' . $unread . ') </h5>';
                $countHead = '<span class="alert-count unreadCount">' . $unread . '</span>
                            <i class="bx bx-bell"></i>';
            }
            return response()->json(['status' => 'success', 'unread' => $unread, 'messages' => $message, 'head' => $head]);
        } else {
            return response()->json(['status' => 'fail', 'msg' => 'no new notification']);
        }
    }
    public function markAsRead($id, $data)
    {
        $noti = Notify::where('id', $id)->first();
        if ($noti->type == "App\Notifications\OrderNotification") {
            $read = auth()->user()->unreadNotifications->where('id', $id)->markAsRead();

            if (Auth::user()->hasRole('Admin')) {
                return redirect('orders');
            } elseif (Auth::user()->hasRole('Store')) {
                return redirect('view/store/orders');
            }
        } elseif ($noti->type == "App\Notifications\TicketNotification") {
            $read = auth()->user()->unreadNotifications->where('id', $id)->markAsRead();

            if (Auth::user()->hasRole('Store')) {
                return redirect('store/ticket');
            } elseif (Auth::user()->type == '2') {
                return redirect('store/ticket');
            }
        }
    }


    public function allNotifications()
    {
        $html = "";
        $user = auth()->user();
        $userNotifications = array();
        $unread = 0;
        $notificationss = $user->notifications
            ->where('read_at', '=', NULL)
            ->all();
        $countHead = "";
        $message = [];
        foreach ($notificationss as $key => $notification) {
            $unread++;
            if ($notification->type == "App\Notifications\AdminNotification") {
                $headerNote = "New notice received";
            } elseif ($notification->type == "App\Notifications\OrderNotification") {
                $headerNote = "New Order placed";
            } elseif ($notification->type == "App\Notifications\TicketNotification") {
                $headerNote = "New Ticket Received";
            }
            $message[$key]['message'] = $headerNote;
            $message[$key]['time'] = $notification->created_at->diffforhumans();
            $message[$key]['link'] = '/markAsRead/' . $notification->id . '/' . $notification->data['id'];
        }
        return view('notifications', compact('unread', 'message'));
    }
}