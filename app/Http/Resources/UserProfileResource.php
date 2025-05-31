<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Stevebauman\Location\Facades\Location;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $timezone               = Location::get('https://' . request()->ip())->timezone;
        $filteredAvailabilities = $this->weeklyAvailabilities->filter(function ($availability) use ($timezone) {
            $currentDate = Carbon::now($timezone)->format('Y-m-d');
            $hasOverride = $this->dateOverrides()
                                ->where('date', $currentDate)
                                ->where('profile_id', $availability->profile_id)
                                ->exists();
            return !$hasOverride;
        });

        return [
            'id'                    => $this->id,
            'title'                 => $this->title,
            'timezone'              => $this->timezone,
            'weekly_availabilities' => WeeklyAvailabilityResource::collection($filteredAvailabilities)
        ];
    }
}
