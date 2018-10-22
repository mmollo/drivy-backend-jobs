const Level = require('./Level.js')
class Level5 extends Level
{
    constructor()
    {
        super(5)
        this.options = {}
        this.input.options.forEach(option => {
            if(!this.options[option.rental_id]) {
                this.options[option.rental_id] = []
            }
            this.options[option.rental_id].push(option.type)
        })
    }

    run()
    {
        let rentals = this.input.rentals.map(rental => {
            let duration = this.getDuration(rental)
            let car = this.cars[rental.car_id]
            let price = this.getDurationPrice(duration, car.price_per_day) + rental.distance * car.price_per_km
            let commission = this.getCommission(price, duration)

            var base_owner = 0
            var base_drivy = 0
            if(rental.id in this.options) {
                this.options[rental.id].forEach(type => {
                    if('gps' === type) {
                        base_owner += duration * 500
                    } else if ('baby_seat' === type) {
                        base_owner += duration * 200
                    } else if ('additional_insurance' === type) {
                        base_drivy += duration * 1000
                    }
                })
            }
            
            return {
                id: rental.id,
                options: this.options[rental.id] || [],
                actions: this.getActions(price, commission, base_owner, base_drivy)
            }
        })

        return {rentals: rentals}
    }

    getActions(price, commission, base_owner = 0, base_drivy = 0)
    {
        return [
            {
                who: 'driver',
                type: 'debit',
                amount: price + base_owner + base_drivy
            },
            {
                who: 'owner',
                type: 'credit',
                amount: price - commission.insurance_fee - commission.assistance_fee - commission.drivy_fee + base_owner
            },
            {
                who: 'insurance',
                type: 'credit',
                amount: commission.insurance_fee
            },
            {
                who: 'assistance',
                type: 'credit',
                amount: commission.assistance_fee
            },
            {
                who: 'drivy',
                type: 'credit',
                amount: commission.drivy_fee + base_drivy
            }                        
        ];
    }
    getCommission(price, duration)
    {
        price *= 0.3;
        return {
            'insurance_fee': price * 0.5,
            'assistance_fee': duration * 100,
            'drivy_fee': price * 0.5 - duration * 100
        }
    }

    getDurationPrice(duration, price_per_day)
    {
        let steps = [
            [1, 10],
            [4, 30],
            [10, 50]
        ]

        var price = 0
        var days, reduction;
        steps.reverse().forEach(step => {
            days = step[0]
            reduction = step[1]
            if(duration <= days) {
                return
            }

            price += (duration - days) * price_per_day * (100 - reduction) / 100;
            duration -= (duration - days);
        })

        return price + duration * price_per_day

    }
}

module.exports = Level5