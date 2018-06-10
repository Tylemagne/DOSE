<?php

//this is where you would build your global reference tables, if you decided you needed any.

$DOSE_GLOBAL_TABLES[1] = new Table
(
    "Global Runescape herb table",
    new Result(14.0, "Guam leaf",    DOSE_TYPE_DUMMY, 2, 1, 1, 0, 0),
    new Result(14.0, "Marrentill",    DOSE_TYPE_DUMMY, 3, 1, 1, 0, 0),
    new Result(14.0, "Tarromin",           DOSE_TYPE_DUMMY, 4, 1, 1, 0, 0),
    new Result(14.0, "Harralander",    DOSE_TYPE_DUMMY, 5, 1, 1, 0, 0),
    new Result(14.0, "Ranarr weed",    DOSE_TYPE_DUMMY, 6, 1, 1, 0, 0),
    new Result(14.0, "Toadflax",    DOSE_TYPE_DUMMY, 7, 1, 1, 0, 0),
    new Result(14.0, "Irit leaf",    DOSE_TYPE_DUMMY, 8, 1, 1, 0, 0),
    new Result(14.0, "Avantoe",    DOSE_TYPE_DUMMY, 9, 1, 1, 0, 0),
    new Result(14.0, "Kwuarm",    DOSE_TYPE_DUMMY, 10, 1, 1, 0, 0),
    new Result(14.0, "Snapdragon",    DOSE_TYPE_DUMMY, 11, 1, 1, 0, 0),
    new Result(14.0, "Cadantine",    DOSE_TYPE_DUMMY, 12, 1, 1, 0, 0),
    new Result(14.0, "Lantadyme",    DOSE_TYPE_DUMMY, 13, 1, 1, 0, 0),
    new Result(14.0, "Dwarf weed",    DOSE_TYPE_DUMMY, 14, 1, 1, 0, 0),
    new Result(13.0, "Torstol",    DOSE_TYPE_DUMMY, 15, 1, 1, 0, 0) //13, to pop the end of the table a bit in the event that another is ever reduced for any reason
);

$DOSE_GLOBAL_TABLES[2] = new Table
(
    "Global potion ingredient table",
    new Result(4.0, "Salna loaf",    DOSE_TYPE_DUMMY, 2, 1, 3, 0, 0),
    new Result(4.0, "Widow snaps",    DOSE_TYPE_DUMMY, 3, 2, 4, 0, 0),
    new Result(4.0, "Phira root",           DOSE_TYPE_DUMMY, 1, 1, 30, 0, 0),
    new Result(4.0, "Popleaf",    DOSE_TYPE_DUMMY, 3, 2, 4, 0, 0)
);

?>