defmodule Drivy do
  @moduledoc """
  Documentation for Drivy.
  """

  def load(level) do
    {input(level), output(level)}
  end

  defp input(level) do
    getFilePath(level, "input.json") |> loadJson
  end

  defp output(level) do
    getFilePath(level, "expected_output.json") |> loadJson
  end

  def fixDate(date) do
    date =
      date
      |> String.split("-")

    day =
      date
      |> List.last()

    day =
      case String.length(day) > 1 do
        false -> "0" <> day
        true -> day
      end

    List.replace_at(date, 2, day)
    |> Enum.join("-")
  end

  defp getFilePath(level, file) do
    [getDataPath(level), file] |> Path.join()
  end

  defp loadJson(file) do
    file
    |> File.read!()
    |> Poison.decode!()
  end

  defp getDataPath(level) do
    [File.cwd!(), "..", "jobs/backend/", "level#{level}", "data"]
    |> Path.join()
    |> Path.expand()
  end

  def to_map(input) do
    Enum.reduce(input, %{}, fn x, acc -> Map.put(acc, x["id"], x) end)
  end

  def to_days(start_date, end_date) do
    start_date = Drivy.fixDate(start_date)
    end_date = Drivy.fixDate(end_date)
    start_date = Date.from_iso8601!(start_date)
    end_date = Date.from_iso8601!(end_date)

    Date.diff(end_date, start_date) + 1
  end
end
