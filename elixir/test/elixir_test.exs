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

  test "Level 3" do
    {input, output} = Drivy.load(3)
    assert Drivy.Level3.run(input) == output
  end

  test "Level 4" do
    {input, output} = Drivy.load(4)
    assert Drivy.Level4.run(input) == output
  end
end
