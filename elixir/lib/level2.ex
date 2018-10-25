defmodule Drivy.Level2 do
  import Drivy

  def run(input) do
    cars = to_map(input["cars"])
    rentals = input["rentals"]

    rentals =
      Enum.map(rentals, fn r ->
        car = Map.get(cars, r["car_id"])
        %{"id" => r["id"], "price" => get_price(car, r)}
      end)

    %{"rentals" => rentals}
  end

  defp get_price(car, rental) do
    car["price_per_km"] * rental["distance"] +
      get_reduction(to_days(rental["start_date"], rental["end_date"]), car["price_per_day"])
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
