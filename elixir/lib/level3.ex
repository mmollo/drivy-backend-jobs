defmodule Drivy.Level3 do
  import Drivy

  def run(input) do
    cars = to_map(input["cars"])
    rentals = input["rentals"]

    rentals =
      Enum.map(rentals, fn r ->
        car = Map.get(cars, r["car_id"])
        duration = to_days(r["start_date"], r["end_date"])
        price = get_price(car, r)

        %{
          "id" => r["id"],
          "price" => price,
          "commission" => get_commission(price, duration)
        }
      end)

    %{"rentals" => rentals}
  end

  defp get_price(car, rental) do
    duration = to_days(rental["start_date"], rental["end_date"])
    car["price_per_km"] * rental["distance"] + get_reduction(duration, car["price_per_day"])
  end

  def get_commission(price, duration) do
    price = price * 0.3

    %{
      "insurance_fee" => price * 0.5,
      "assistance_fee" => duration * 100,
      "drivy_fee" => price * 0.5 - duration * 100
    }
  end

  def get_reduction(duration, price_per_day) do
    cond do
      duration > 10 -> (duration - 10) * price_per_day * 0.5 + get_reduction(10, price_per_day)
      duration > 4 -> (duration - 4) * price_per_day * 0.7 + get_reduction(4, price_per_day)
      duration > 1 -> (duration - 1) * price_per_day * 0.9 + get_reduction(1, price_per_day)
      true -> duration * price_per_day
    end
  end
end
