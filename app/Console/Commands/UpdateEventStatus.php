<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Guest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\FeedbackRequestMail;

class UpdateEventStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-event-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the status of events to complete if they occurred 2 days ago and sends feedback emails.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Get events that occurred 2 days ago and are not yet marked as complete
            $events = Event::where('status', '!=', 'complete')
                ->whereDate('date', '=', Carbon::now()->subDays(2)->toDateString())
                ->get();

            if ($events->isEmpty()) {
                $this->info('No events to update.');
                return Command::SUCCESS;
            }

            foreach ($events as $event) {
                // Update event status to 'complete'
                $event->update(['status' => 'complete']);

                // Trigger feedback emails for guests
                $this->sendFeedbackEmails($event);

                $this->info("Event ID {$event->id} status updated to 'complete' and feedback emails sent.");
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error updating event statuses: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Send feedback emails to guests.
     *
     * @param \App\Models\Event $event
     * @return void
     */
    protected function sendFeedbackEmails(Event $event)
    {
        // Retrieve all guests for the event
        $guests = Guest::where('event_id', $event->id)->get();

        // Iterate over each guest and send an email
        foreach ($guests as $guest) {
            Mail::to($guest->email)->send(new FeedbackRequestMail($event, $guest));
        }
    }
}
