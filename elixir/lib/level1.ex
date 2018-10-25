defmodule Drivy.Level1 do
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
      car["price_per_day"] * to_days(rental["start_date"], rental["end_date"])
  end
end
