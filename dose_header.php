<?PHP

//DOSE ALPHA. Mostly complete. Needs some more cleanup and completion.


define("DOSE_TYPE_MAGICPOINTER_MULTSINGLE", -102, true);
define("DOSE_TYPE_MAGICPOINTER_MULTMANY", -101, true);
define("DOSE_TYPE_DUMMY", 0, true);
define("DOSE_TYPE_ITEM", 0, true);


$DOSE_GLOBAL_TABLES = array(); //cannot contain more magic pointers
include_once("dose_global_tables.php");





function AddRewardTo($rewardx, $totalx)
{
    if( !$rewardx ) echo "NO REWARDX!";

    $returnedTotal = $totalx;


    for ($i=0; $i < count($totalx); $i++)
    {
        if($totalx[$i] == NULL) continue;

        if($rewardx->rwName == $totalx[$i]->rwName)
        {
            echo "REWARD EXISTS IN BLOCK, ADDING QUANTITY...<br>";
        }
    }


    return $returnedTotal;
}


class Result
{
    public $NAME;
    public $DENOMINATOR;
    public $TYPE;
    public $ID;
    public $QMIN;
    public $QMAX;
    public $ARG3;
    public $ARG4;

    function __construct($d, $n, $t, $i, $min, $max, $a3, $a4)
    {
        $this->DENOMINATOR = $d;
        $this->NAME = $n;
        $this->TYPE   = $t;
        $this->ID   = $i;
        $this->QMIN = $min;
        $this->QMAX = $max;
        $this->ARG3 = $a3;
        $this->ARG4 = $a4;
    }

    function ToReward() //put in Result class
    {
        return new Reward( $this->NAME, $this->TYPE, $this->ID, mt_rand( $this->QMIN, $this->QMAX ) );
    }

}




class Reward //migrate from result to this, more complete debug info is here
{
    public $rwName;
    public $rwType;
    public $rwID;
    public $rwQty;
    public $rwParentTable;

    function __construct($n, $type, $id, $q)
    {
        $this->rwName = $n;
        $this->rwType = $type;
        $this->rwID = $id;
        $this->rwQty = $q;
    }


    //stuff from original Result object, Table object, or Scenario object
}



class Table
{
    public $NAME;
    public $TOTALCHANCE; //can be incorrect ONLY calculated on construct, not on AddResult!!!
        //public $AMPLIFIER;

    public $RESULTS;
    

    function __construct()
    {
        $argc = func_num_args();
        $prevars = 1; //number of variables before tables begin
        $resc = $argc - $prevars;

        if($argc < 2) die("Too few args for Table - need at least a name and one Result!");

        if( !is_string(func_get_arg(0)) ) die("needs a name!");

        $this->NAME = func_get_arg(0);

        $this->TOTALCHANCE = 0; //initialize at 0

        for ($i=$prevars; $i <= $resc ; $i++)
        {
            $currentResult = func_get_arg($i);
            $this->RESULTS[] = $currentResult;

            if($currentResult->DENOMINATOR != 0)
            {
                $this->TOTALCHANCE += (float) 1/$currentResult->DENOMINATOR;
            }
        }
    }







    function MagicPointerRoll() //MPR is a special method for pointers. It's simple and immune to all effects.
    {

        $PRECISION = 1000000000;
        $rand = (float) mt_rand(0, $PRECISION);
        $rand /= $PRECISION;

        $runningtotal = 0.0;
        $chancetotal = 0.0;
        


        for ($currentResult=0; $currentResult < count($this->RESULTS); $currentResult++) //iterates each result
        {
            
            $denom = $this->RESULTS[$currentResult]->DENOMINATOR;

            if ($denom == 0.0) $denom = 1.0;
            $chance = (float) 1 / $denom; //calculate a chance for the current result (ex: 0.25)


            $chancetotal += $chance;

            if($rand <= $chance + $runningtotal)
            {
                $temptotal = $runningtotal + $chance;
                //echo $this->RESULTS[$currentResult]->NAME." is the winner!!!<br>";
                return $this->RESULTS[$currentResult];
                //$this->AddReward( $this->TABLES[$currentTable]->RESULTS[$currentResult]->ToReward() );
                break;
            }
            else
            {
                $runningtotal += $chance;
                //continue on
                
            }
            
        }

        echo "MPR finished without a roll, rand is ".$rand."!<br>";
        

            //echo "[CHANCE:".$chancetotal."]<br>";
    }







    function GetTotal()
    {
        $rollingtotal = 0.0;
        for ($currentResult=0; $currentResult < count($this->RESULTS); $currentResult++)
        {
            if($this->RESULTS[$currentResult]->DENOMINATOR == 0)
            {
                $this->RESULTS[$currentResult]->DENOMINATOR = 1;
            }

            $rollingtotal += (float) 1 / $this->RESULTS[$currentResult]->DENOMINATOR;
        }

        $rollingtotal = round($rollingtotal, 5);
        return $rollingtotal;
    }
}


class Scenario
{
    public $NAME;
    public $DAMPORAMP; //true=amp, false=damp
    public $DAMPAMPMULT; //the actual multiplier


    public $TABLES;
    //public $FINALRESULTS;
    //public $finalRewards;
    public $finalRewards2;

    function __construct()
    {
        //print_r(func_get_args());
        $argc = func_num_args();
        $prevars = 1; //number of variables before tables begin
        $tabc = $argc - $prevars;
        $this->DAMPAMPMULT = 1.0;

        if($argc < 2) die("Too few args for Scenario - need at least a name and one Table!");

        if( !is_string(func_get_arg(0)) ) die("needs a name!");
        $this->NAME = func_get_arg(0);

        //$this->TABLES = array();


        for ($i=$prevars; $i <= $tabc; $i++)
        {
            $this->TABLES[] = func_get_arg($i);
            
        }

    }

    function AddReward($reward)
    {
        //echo "adding reward:'".$reward->rwName."' to stack<br>";

        if( !$reward ) echo "NO REWARD!";

        //echo "Adding ".$rewardx->rwName."<br>";

        $foundExistingRewardID = -1;

        for ($i=0; $i < count($this->finalRewards2); $i++)
        {
            //if($total[$i] == NULL) continue;


            if( $reward->rwType === $this->finalRewards2[$i]->rwType && $reward->rwID === $this->finalRewards2[$i]->rwID )
            {

                if($reward->rwName != $this->finalRewards2[$i]->rwName)
                {
                    echo "<span style='color:purple;'>WARNING-TYPE AND ID ARE THE SAME BUT NAMES DIFFER!</span><br>";
                }

                $foundExistingRewardID = $i;
                $this->finalRewards2[$i]->rwQty += $reward->rwQty;
                break;
            }
        }

        if( $foundExistingRewardID === -1 )
        {
            $this->finalRewards2[] = $reward;
            //echo "**^ADDED NEW**<br>";
        }
    }


    function Roll()
    {
       global $DOSE_GLOBAL_TABLES;

        
        for ($currentTable=0; $currentTable < count($this->TABLES); $currentTable++) //iterates each table
        {

            //$this->TABLES[$currentTable]->MagicPointerRoll($this->finalRewards2);

            $PRECISION = 1000000000;
            $rand = (float) mt_rand(0, $PRECISION); //roll a number between 0 and a billion
            $rand /= $PRECISION; //convert to a float, 0.000000000 to 1.000000000
            if( func_num_args()==1 && is_float(func_get_arg(0)) ) $rand = func_get_arg(0); //diagnostic reasons
            $runningtotal = 0.0;
            $chancetotal = 0.0;

            if($this->DAMPORAMP === false && $this->TABLES[$currentTable]->TOTALCHANCE * $this->DAMPAMPMULT > 1.0)
            {
                echo "{{WARNING - AMP POPPED TABLE #".$currentTable."!}}<br>";
            }

            for ($currentResult=0; $currentResult < count($this->TABLES[$currentTable]->RESULTS); $currentResult++) //iterates each result
            {
                $denom = $this->TABLES[$currentTable]->RESULTS[$currentResult]->DENOMINATOR;

                if($denom == 0.0) //Results with a denominator of 0.0 are 100% static and immune to dampeneing
                {

                    $this->AddReward( $this->TABLES[$currentTable]->RESULTS[$currentResult]->ToReward() );
                    break;
                }

                $chance = (float) 1 / $denom; //calculate a chance for the current result (ex: 0.25)
                //echo "<".$chance.">";

                if($this->DAMPORAMP == true)
                {
                    $chance /= $this->DAMPAMPMULT;
                    //echo "---DAMPENED TO ".$chance;
                }
                else if ($this->DAMPORAMP == false)
                {
                    $chance *= $this->DAMPAMPMULT;
                }

                $chancetotal += $chance;
                

                if($rand <= $chance + $runningtotal)
                {
                    $temptotal = $runningtotal + $chance;


                    if($this->TABLES[$currentTable]->RESULTS[$currentResult]->TYPE == DOSE_TYPE_MAGICPOINTER_MULTSINGLE || $this->TABLES[$currentTable]->RESULTS[$currentResult]->TYPE == DOSE_TYPE_MAGICPOINTER_MULTMANY)
                    {

                        $ptrWinnerID = $this->TABLES[$currentTable]->RESULTS[$currentResult]->ID;
                        //echo $ptrWinnerID." IS OUR GLOBAL TABLE<br>";
                        $quantity = mt_rand($this->TABLES[$currentTable]->RESULTS[$currentResult]->QMIN, $this->TABLES[$currentTable]->RESULTS[$currentResult]->QMAX);

                        //for loop
                        $ptrResult = $DOSE_GLOBAL_TABLES[$ptrWinnerID]->MagicPointerRoll();



                        if($ptrResult != NULL)
                        {
                            //set qty
                            $ptrResult = $ptrResult->ToReward();
                            //$originalQuantity = $ptrResult->rwQty;

                            if( $this->TABLES[$currentTable]->RESULTS[$currentResult]->TYPE == DOSE_TYPE_MAGICPOINTER_MULTSINGLE )
                            {
                                $ptrResult->rwQty *= $quantity;
                                $this->AddReward( $ptrResult );
                            }
                        }
                        else
                        {
                            echo "NULL 1!";
                        }

                        if( $this->TABLES[$currentTable]->RESULTS[$currentResult]->TYPE == DOSE_TYPE_MAGICPOINTER_MULTMANY )
                        {
                            for ($i=0; $i < $quantity; $i++)
                            { 
                                //echo "multmany";
                                $ptrResult = $DOSE_GLOBAL_TABLES[$ptrWinnerID]->MagicPointerRoll();
                                
                                if($ptrResult != NULL)
                                {
                                    $ptrResult = $ptrResult->ToReward();
                                    $this->AddReward( $ptrResult );
                                }
                                else
                                {
                                    echo "NULL ROLL!";
                                }
                            }
                        }

                            
                            
                        

                        break;
                    }







                    //$this->finalRewards[$currentTable] = $this->TABLES[$currentTable]->RESULTS[$currentResult]->ToReward();
                    $this->AddReward( $this->TABLES[$currentTable]->RESULTS[$currentResult]->ToReward() );
                    break;
                }
                else
                {
                    $runningtotal += $chance;
                    //continue on
                    
                }
                //$this->finalRewards2[$currentTable] = NULL;
                //echo "{".$this->TABLES[$i]->RESULTS[$i2]->NAME."}<br>";
                //$this->FINALRESULTS[] = $this->TABLES[$i]->RESULTS[$i2];

            }

            
        }
    }



    function ClampTable($t)
    {

    }

    function Amplify($a)
    {


        $this->DAMPORAMP = false;
        $this->DAMPAMPMULT = $a;


    }

    function AddResult($t, $r, $nd)
    {
        if( !is_a($r, "Result") )
        {
            die("AddResult() needs a result for r.");
        }

        $temp_r = clone $r;
        $temp_r->DENOMINATOR = $nd;

        $this->TABLES[$t]->RESULTS[] = $temp_r;
    }

    function PullResults($t, $n)
    {
        $match = NULL;
        $result_return = NULL;

        for ($currentResult=0; $currentResult < count($this->TABLES[$t]->RESULTS); $currentResult++)
        {
            if($this->TABLES[$t]->RESULTS[$currentResult]->NAME === $n)
            {


                $result_return = clone $this->TABLES[$t]->RESULTS[$currentResult];
                unset($this->TABLES[$t]->RESULTS[$currentResult]);

            }

            
        }

        $this->TABLES[$t]->RESULTS = array_values($this->TABLES[$t]->RESULTS);

        return $result_return;
    }

    function CreateTable($n, $r)
    {
        $this->TABLES[] = new Table($n, $r);
        return count($this->TABLES)-1;
    }



    function Dampen($d)
    {
        $this->DAMPORAMP = true;
        $this->DAMPAMPMULT = $d;
    }

    function GetRoll()
    {
        return $this->finalRewards2;
        //$this->Rewards[]
    }

    function Reset()
    {
        $this->finalRewards2 = NULL;
    }


    function RollMultiple(int $mult)
    {
        echo "rolling multiple times".$mult;
        for ($i=0; $i < $mult; $i++)
        { 
            $this->Roll();
        }
    }


    function RollMultiple2(int $mult)
    {
        $finalMultRewards = NULL;
        $this->Reset();


        for ($i=0; $i < $mult; $i++) //repeat equal to multiplier
        {
            $this->Roll();
            //echo "m<br>";

            if($i === 0)//first run
            {
                $finalMultRewards = $this->finalRewards2;
                
                echo "First RollMultiple<br>";
                //print_r($this->finalRewards);
                //echo "<br>";
                continue;
            }


            //increment through rolled rewards
            for ($i2=0; $i2 < count($this->finalRewards2); $i2++)
            {
                if( $this->finalRewards2[$i2] != NULL )
                {
                    $finalMultRewards = AddRewardTo( $this->finalRewards2[$i2], $finalMultRewards );
                }
            }



            $this->Reset();

        }











        $this->finalRewards2 = $finalMultRewards;
        echo "-end of RollMultiple. Final:<br>";
        print_r($finalMultRewards);
    }












    function DumpRewardJSON()
    {
        echo "<pre>";
        print_r( json_encode($this->finalRewards2, JSON_PRETTY_PRINT) );
        echo "</pre>";
    }


    function DumpReward()
    {
        echo "<pre>";
        print_r($this->finalRewards2);
        echo "</pre>";
    }

    function DumpStructure()
    {
        echo "<pre>";
        print_r($this);
        echo "</pre>";
    }

    function PrintReward()
    {
        for ($i=0; $i < count($this->finalRewards); $i++)
        { 
            if($this->finalRewards[$i])
                {
                    echo $this->finalRewards[$i]->rwName." x ".$this->finalRewards[$i]->rwQty;
                    echo "<br>";
                }
        }
    }

    function DumpStructureJSON()
    {
        echo "<pre>";
        print_r( json_encode($this, JSON_PRETTY_PRINT) );
        echo "</pre>";
    }

    function PrintTotals()
    {
        for ($currentTable=0; $currentTable < count($this->TABLES); $currentTable++)
        {
            $fill = $this->TABLES[$currentTable]->GetTotal();
            $fill *= 100;
            $ampfill = -1;

            if($this->DAMPORAMP === false && $this->DAMPAMPMULT != 1.0)
            {
                //echo "amping!";
                $ampfill = $fill * $this->DAMPAMPMULT;
                $ampfill = round($ampfill, 3);
            }
            else if ($this->DAMPORAMP === true && $this->DAMPAMPMULT != 1.0)
            {
                //echo "dampening!";
                $ampfill = $fill / $this->DAMPAMPMULT;
                $ampfill = round($ampfill, 3);
            }

            echo "Table '".$this->TABLES[$currentTable]->NAME."' fill: ".$fill."% ";

            if($this->DAMPAMPMULT != 1.0 && $fill != 100)
            {
                echo "(".$ampfill."%)<br>";
            }
            else
            {
                echo "<br>";
            }

            
        }
    }


}




?>







