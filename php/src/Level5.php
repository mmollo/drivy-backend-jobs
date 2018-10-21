<?php declare(strict_types=1);

namespace DJB;

class Level5 extends Level
{
    public function __construct()
    {
        parent::__construct(5);
    }

    public function run()
    {
        $cars = [];
        foreach($this->input->cars as $car) {
            $cars[$car->id] = $car;
        }

        $options = [];
        foreach($this->input->options as $option) {
            if(!isset($options[$option->rental_id])) {
                $options[$option->rental_id] = [];
            }
            $options[$option->rental_id][] = $option->type;
        }
        
        $rentals = [];
        foreach($this->input->rentals as $rental) {
            $car = $cars[$rental->car_id];
            $duration = (new \DateTime($rental->end_date))->diff(new \DateTime($rental->start_date))->format("%a") + 1;
            
            $price = $rental->distance * $car->price_per_km + $this->durationPrice($duration, $car->price_per_day);
            
            $commission = $this->getCommission($price, $duration);
            
            $base_owner = 0;
            $base_drivy = 0;
            if(array_key_exists($rental->id, $options)) {
                
                
                foreach($options[$rental->id] as $type) {
                    if('gps' === $type) {
                        $base_owner += $duration * 500;
                    } elseif('baby_seat' === $type) {
                        $base_owner += $duration * 200;
                    } elseif('additional_insurance' === $type) {
                        $base_drivy += $duration * 1000;
                    }
                }
            }

            $actions = $this->getActions($price, $commission, $base_owner, $base_drivy);

            $rentals[] = (object)[
                'id' => $rental->id,
                'options' => $options[$rental->id] ?? [],
                'actions' => $actions
            ];
        }

        $output = (object)[
            'rentals' => $rentals
        ];

        return $output;       
    }

    private function getActions(int $price, $commission, $base_owner = 0, $base_drivy = 0)
    {
        return [
            (object)[
                'who' => 'driver',
                'type' => 'debit',
                'amount' => $price + $base_owner + $base_drivy
            ],
            (object)[
                'who' => 'owner',
                'type' => 'credit',
                'amount' => $price - $commission->insurance_fee - $commission->assistance_fee - $commission->drivy_fee + $base_owner
            ],
            (object)[
                'who' => 'insurance',
                'type' => 'credit',
                'amount' => $commission->insurance_fee
            ],
            (object)[
                'who' => 'assistance',
                'type' => 'credit',
                'amount' => $commission->assistance_fee
            ],
            (object)[
                'who' => 'drivy',
                'type' => 'credit',
                'amount' => $commission->drivy_fee + $base_drivy
            ]                        
        ];
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
