<?php

namespace App\Livewire;
use App\Models\Course;
use Livewire\Component;

class AvailableSeats extends Component
{

    public $course;
    public $available;

    public function mount($courseId)
    {
        $this->course = Course::findOrFail($courseId);
        $this->calculateAvailable();
    }

    public function calculateAvailable()
    {

        $fresh = Course::select('available_seats', 'total_seats')->find($this->course->id);

        $this->available =  $fresh->available_seats;
    }
    public function render()
    {
          $this->calculateAvailable();
        return view('livewire.available-seats');
    }
}
