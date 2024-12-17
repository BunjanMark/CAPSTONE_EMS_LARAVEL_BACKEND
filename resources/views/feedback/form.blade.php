@extends('layouts.app')

@section('content')
<div class="container" style="background-color: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); display: flex; justify-content: center; align-items: center; flex-direction: column;">
    <div id="services">
        <!-- The services will be dynamically loaded here -->
    </div>
    <h1 class="text-center" 
    style="color: #DAA520; 
           font-family: 'Arial', sans-serif; 
           font-size: 2.5rem; 
           font-weight: bold; 
           margin-bottom: 20px; 
           text-transform: uppercase; 
           letter-spacing: 2px; 
           text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);">
    Feedback for Event: {{ $event->name ?? 'Unknown Event' }}  
    -- Guest: {{ $guest->GuestName }}
</h1>

    <form id="feedbackForm" style="width: 100%; max-width: 800px; display: flex; flex-direction: column; align-items: center;">
        @csrf
        <input type="hidden" name="event_id" value="{{ $eventId }}">
        <input type="hidden" name="customer_id" value="{{ $guestId }}">
        <input type="hidden" name="customer_name" value="{{ $guestId }}">
        <!-- Feedback for dynamically included categories -->
        @if (in_array('Catering', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="catering_feedback" style="color: #DAA520; margin-bottom: 8px;">Catering Feedback</label>
                <textarea name="catering_feedback" id="catering_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Food Catering', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="food_catering_feedback" style="color: #DAA520; margin-bottom: 8px;">Food Catering Feedback</label>
                <textarea name="food_catering_feedback" id="food_catering_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
    
        @if (in_array('Decoration', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="decoration_feedback" style="color: #DAA520; margin-bottom: 8px;">Decoration Feedback</label>
                <textarea name="decoration_feedback" id="decoration_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
    
        @if (in_array('Photography', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="photography_feedback" style="color: #DAA520; margin-bottom: 8px;">Photography Feedback</label>
                <textarea name="photography_feedback" id="photography_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
    
        @if (in_array('Videography', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="videography_feedback" style="color: #DAA520; margin-bottom: 8px;">Videography Feedback</label>
                <textarea name="videography_feedback" id="videography_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Host', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="host_feedback" style="color: #DAA520; margin-bottom: 8px;">Host Feedback</label>
                <textarea name="host_feedback" id="host_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Entertainment', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="entertainment_feedback" style="color: #DAA520; margin-bottom: 8px;">Entertainment Feedback</label>
                <textarea name="entertainment_feedback" id="entertainment_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Sound', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="sound_feedback" style="color: #DAA520; margin-bottom: 8px;">Sound Feedback</label>
                <textarea name="sound_feedback" id="sound_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Marketing', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="marketing_feedback" style="color: #DAA520; margin-bottom: 8px;">Marketing Feedback</label>
                <textarea name="marketing_feedback" id="marketing_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Trasportation', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="transportation_feedback" style="color: #DAA520; margin-bottom: 8px;">Transportation Feedback</label>
                <textarea name="transportation_feedback" id="transportation_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Accomodation', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="accommodation_feedback" style="color: #DAA520; margin-bottom: 8px;">Accommodation Feedback</label>
                <textarea name="accommodation_feedback" id="accommodation_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Venue', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="venue_feedback" style="color: #DAA520; margin-bottom: 8px;">Venue Feedback</label>
                <textarea name="venue_feedback" id="venue_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Lighting', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="lighting_feedback" style="color: #DAA520; margin-bottom: 8px;">Lighting Feedback</label>
                <textarea name="lighting_feedback" id="lighting_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Venue Management', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="venue_management_feedback" style="color: #DAA520; margin-bottom: 8px;">Venue Management Feedback</label>
                <textarea name="venue_management_feedback" id="venue_management_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif
        @if (in_array('Other', $services))
            <div class="form-group" style="display: flex; flex-direction: column; align-items: center; margin-bottom: 20px;">
                <label for="other_feedback" style="color: #DAA520; margin-bottom: 8px;">Other Feedback</label>
                <textarea name="other_feedback" id="other_feedback" class="form-control" rows="3" style="border-color: #DAA520;"></textarea>
            </div>
        @endif

        <div class="text-center">
            <button type="button" id="submitFeedback" class="btn" style="background-color: #DAA520; color: white; padding: 10px 20px; border-radius: 5px; font-size: 16px; border: none;">Submit Feedback</button>
        </div>
    </form>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const eventId = "{{ $event->id }}";  // Dynamic eventId from backend
    const guestId = "{{ $guest->id }}";  // Dynamic guestId from backend
    const guestName = "{{ $guest->GuestName }}";  // Dynamic guest name from backend

    document.querySelector('input[name="event_id"]').value = eventId;
    document.querySelector('input[name="customer_id"]').value = guestId;

    document.getElementById("submitFeedback").addEventListener("click", function() {
        const feedbackData = {
            venue_feedback: document.getElementById("venue_feedback")?.value || '',
            catering_feedback: document.getElementById("catering_feedback")?.value || '',
            decoration_feedback: document.getElementById("decoration_feedback")?.value || '',
            food_catering_feedback: document.getElementById("food_catering_feedback")?.value || '',
            accommodation_feedback: document.getElementById("accommodation_feedback")?.value || '',
            transportation_feedback: document.getElementById("transportation_feedback")?.value || '',
            photography_feedback: document.getElementById("photography_feedback")?.value || '',
            videography_feedback: document.getElementById("videography_feedback")?.value || '',
            host_feedback: document.getElementById("host_feedback")?.value || '',
            entertainment_feedback: document.getElementById("entertainment_feedback")?.value || '',
            sound_feedback: document.getElementById("sound_feedback")?.value || '',
            lighting_feedback: document.getElementById("lighting_feedback")?.value || '',
            venue_management_feedback: document.getElementById("venue_management_feedback")?.value || '',
            marketing_feedback: document.getElementById("marketing_feedback")?.value || '',
            other_feedback: document.getElementById("other_feedback")?.value || '',
            event_id: eventId,
            customer_name: guestName,
            customer_id: guestId,
        };

        console.log("Feedback Data:", feedbackData);

        // Submit the feedback data to the server #TODO should be dynamic
        // port 5000
        fetch("https://eventwise-eventmanagementsystem.onrender.com/submit_feedback", { 
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(feedbackData),
        })
        .then((response) => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then((data) => {
            console.log("Success:", data);
            alert("Feedback submitted successfully!");
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("Failed to submit feedback.");
        });
    });
});
</script>
@endsection
