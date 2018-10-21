<?php declare(strict_types=1);

namespace DJB;

class Level3 extends Level
{
    public function __construct()
    {
        parent::__construct(3);
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
            
            $price = $rental->distance * $car->price_per_km + $this->durationPrice($duration, $car->price_per_day);
            
            $commission = $this->getCommission($price, $duration);
            $rentals[] = (object)[
                'id' => $rental->id,
                'price' => $price,
                'commission' => $commission
            ];
        }

        $output = (object)[
            'rentals' => $rentals
        ];

        return $output;       
    }

    private function getCommission(int $price, int $duration)
    {
        $price *= 0.3;
        return (object)[
            'insurance_fee' => (int)($price * 0.5),
            'assistance_fee' => $duration * 100,
            'drivy_fee' => (int)($price * 0.5) - $duration * 100
        ];
    }

    private function durationPrice(int $duration, int $price_per_day)
    {
        $steps = [
            1 => 10,
            4 => 30,
            10 => 50
        ];

        krsort($steps);

        $price = 0;
        foreach($steps as $days => $reduction) {
            if($duration <= $days) {
                continue;
            }

            $price += ($duration - $days) * $price_per_day * (100 - $reduction) / 100;
            $duration -= ($duration - $days);

        }
        
        return $price + $duration * $price_per_day;
    }
}
