defmodule DrivyTest do
  use ExUnit.Case
  doctest Drivy

  test "Level 1" do
    {input, output} = Drivy.load(1)
    assert Drivy.Level1.run(input) == output
  end

  test "Level 2" do
    {input, output} = Drivy.load(2)
    assert Drivy.Level2.run(input) == output
  end
end
