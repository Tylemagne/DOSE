<?PHP
require("dose_header.php");

echo "Testing a DOSE scenario...";

$sTestingPointers = new Scenario
(
    "Testing pointers",
    new Table
    (
        "Main table",
        new Result(3.0, "Grab 2-3 rewards from global 1", DOSE_TYPE_MAGICPOINTER_MULTMANY, 1, 2, 3, 0, 0),
        new Result(3.0, "Grab 1 reward from global 2", DOSE_TYPE_MAGICPOINTER_MULTMANY, 2, 1, 1, 0, 0),
        new Result(3.0, "Steel sword", DOSE_TYPE_ITEM, 251, 1, 1, 0, 0) //Steel sword is itemid 251
    ),
    new Table
    (
        "Table channel 2 - 1/300 for 1-2 Fire Rubies",
        new Result(300.0, "Fire ruby", DOSE_TYPE_ITEM, 9031, 1, 2, 0, 0)
    )
);


$sTestingPointers->Roll();

echo "<hr>";

$sTestingPointers->DumpRewardJSON();
$sTestingPointers->PrintTotals();


?>