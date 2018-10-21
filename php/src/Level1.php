<?php declare(strict_types=1);

namespace DJB;

class Level1 extends Level
{
    public function __construct()
    {
        parent::__construct(1);
    }

    public function run()
    {
        $cars = [];
        foreach($this->input->cars as $car) {
            $cars[$car->id] = $car;
        }
        
        $rentals = [];
        foreach($this->input->rentals as $rental) {
            $car = $cars[$rental->car_id];
            $duration = (new \DateTime($rental->end_date))->diff(new \DateTime($rental->start_date))->format("%a") + 1;
            
            $price = $rental->distance * $car->price_per_km + $duration * $car->price_per_day;
            $rentals[] = (object)[
                'id' => $rental->id,
                'price' => $price
            ];
        }

        $output = (object)[
            'rentals' => $rentals
        ];

        return $output;
    }
}
