<?php

namespace App\Notifications;

use App\Models\Package;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomerPackageRequestNotification extends Notification
{
    use Queueable;
    public $package;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Package $package)
    {
        $this->package = $package;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $url = route("packages.show", ["id" => $this->package->id]);
        $customer = User::find($this->package->customer_id);
        $customer_url = route("customers.show", $customer->id);

        return [
            'package_id' => $this->package->id,
            'message' => '<a class="link-primary" href="' . $customer_url . '" ><strong>' . $customer->name . '</strong></a> has request ' . $this->package->pkg_type . '
            <a class="link-primary" href="' . $url . '" ><strong>Package - PKG #' . $this->package->id . '</strong></a>',
            'url' => $url
        ];
    }
}
