<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 9/20/15
 * Time: 5:58 PM
 */

namespace KaiApp\Controller;

use KaiApp\JsonTransformers\LegendariesTransformer;
use KaiApp\JsonTransformers\LegendaryTransformer;
use KaiApp\JsonTransformers\SimpleTransformer;
use KaiApp\RedBO\RedCrafting;
use KaiApp\RedBO\RedCraftSubItem1;
use KaiApp\RedBO\RedCraftSubItem2;
use KaiApp\RedBO\RedCraftSubItem3;
use KaiApp\RedBO\RedCraftSubItem4;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Slim\Http\Request;
use Slim\Http\Response;

class CraftingController extends BaseController
{
    private $redCrafting;
    private $redCraftSubItem1;
    private $redCraftSubItem2;
    private $redCraftSubItem3;
    private $redCraftSubItem4;

    public function __construct(RedCrafting $_redCrafting,RedCraftSubItem1 $_redCraftSubItem1,
        RedCraftSubItem2 $_redCraftSubItem2,RedCraftSubItem3 $_redCraftSubItem3, RedCraftSubItem4 $_redCraftSubItem4) {
        $this->redCrafting = $_redCrafting;
        $this->redCraftSubItem1 = $_redCraftSubItem1;
        $this->redCraftSubItem2 = $_redCraftSubItem2;
        $this->redCraftSubItem3 = $_redCraftSubItem3;
        $this->redCraftSubItem4 = $_redCraftSubItem4;

        parent::__construct();
    }

    public function get(Request $request,Response $response, array $args) {
        $craft = $this->redCrafting->getWithDetails($args['id']);
        if (empty($craft))
            return $this->response(new Item("invalid id passed",new SimpleTransformer()),$response,500);

        $sub1Items = null;
        $sub2Items = null;
        $sub3Items = null;
        $sub4Items = null;

        $sub1Items = $this->redCraftSubItem1->getAllWithDetails(array($craft[0]["id"]));
        if (!empty($sub1Items)) {
            //Get all sub item level 2
            $sub2Items = $this->redCraftSubItem2->getAllWithDetails($this->getIds($sub1Items));
            if (!empty($sub2Items)) {
                $sub3Items = $this->redCraftSubItem3->getAllWithDetails($this->getIds($sub2Items));
                if (!empty($sub3Items)) {
                    //Get all sub item level 4
                    $sub4Items = $this->redCraftSubItem4->getAllWithDetails($this->getIds($sub3Items));
                }
            }
        }

        //Populate object to be encoded
        if (!empty($sub1Items)) {
            for($i1 = 0; $i1 < sizeof($sub1Items); $i1++) {
                if(!empty($sub2Items)) {
                    $sub1Items[$i1]["sub2Item"] = array();

                    for($i2 = 0; $i2 < sizeof($sub2Items); $i2++) {
                        if ($sub1Items[$i1]["id"] == $sub2Items[$i2]["craftsubitem1_id"]) {
                            if(!empty($sub3Items)) {
                                $sub2Items[$i2]["sub3Item"] = array();

                                for($i3 = 0; $i3 < sizeof($sub3Items); $i3++) {
                                    if ($sub2Items[$i2]["id"] == $sub3Items[$i3]["craftsubitem2_id"]) {
                                        if(!empty($sub4Items)) {
                                            $sub3Items[$i3]["sub4Item"] = array();

                                            for($i4 = 0; $i4 < sizeof($sub4Items); $i4++) {
                                                if ($sub3Items[$i3]["id"] == $sub4Items[$i4]["craftsubitem3_id"]) {

                                                    array_push($sub3Items[$i3]["sub4Item"],$sub4Items[$i4]);
                                                }
                                            }
                                        }

                                        array_push($sub2Items[$i2]["sub3Item"],$sub3Items[$i3]);
                                    }
                                }
                            }
                            array_push($sub1Items[$i1]["sub2Item"],$sub2Items[$i2]);
                        }
                    }
                }
            }

            $craft[0]["sub1Item"] = $sub1Items;

            return $this->response(new Item($craft[0], new LegendaryTransformer()),$response);
        } else
            return $this->response(new Item("An error has occurred", new SimpleTransformer()),$response, 500);
    }

    private function getIds($items) {
        $ids = array();
        $i = 0;
        foreach($items as $item) {
            $ids[$i] = $item["id"];
            $i++;
        }

        return $ids;
    }

    public function all(Request $request,Response $response, array $args) {
        $crafts = $this->redCrafting->getAllWithDetails();
        if (empty($crafts))
            return $this->response(new Item("No crafts found",new SimpleTransformer()),$response, 404);

        return $this->response(new Collection($crafts, new LegendariesTransformer()),$response);
    }

    public function reset(Request $request,Response $response, array $args) {
        $this->redCraftSubItem4->wipe();
        $this->redCraftSubItem3->wipe();
        $this->redCraftSubItem2->wipe();
        $this->redCraftSubItem1->wipe();
        $this->redCrafting->wipe();
        $this->addAll();

        return $this->response(new Item("Legendaries have been reset",new SimpleTransformer()),$response);
    }

    private function addAll()
    {
        $this->addBifrost();
        $this->addBolt();
        $this->addEternity();
        $this->addFlameseeker();
        $this->addFrenzy();
        $this->addFrostfang();
        $this->addHowler();
        $this->addIncinerator();
        $this->addJuggernaught();
        $this->addKamohoali();
        $this->addKraitkin();
        $this->addKudzu();
        $this->addMeteorlogicus();
        $this->addMoot();
        $this->addQuip();
        $this->addRodgort();
        $this->addSunrise();
        $this->addDreamer();
        $this->addMinstrel();
        $this->addPredator();
        $this->addTwilight();
    }

    private function addTwilight() {
        //Twilight
        $craftId = $this->redCrafting->add(30704);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Twilight
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19648,1);

            //Gift of Darkness
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19631,1);

            $this->redCraftSubItem3->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19664,1);//Gift of Ascalon
            $this->redCraftSubItem4->add($subitem3Id,16982,500);//Ascalonian Tear

            $this->redCraftSubItem3->add($subitem2Id,24310,100);//Onyx Lodestone
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
        //Superior Sigil of Blood
        $this->redCraftSubItem2->add($subitem1Id,24570,1);

        //Dusk
        $this->redCraftSubItem1->add($craftId,29185,1);
    }

    private function addPredator() {
        //The Predator
        $craftId = $this->redCrafting->add(30694);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Predator
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19661,1);

            //Gift of Stealth
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19636,1);

            $this->redCraftSubItem3->add($subitem2Id,24310,100);//Onyx Lodestone
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19671,1);//Gift of Knowledge
            $this->redCraftSubItem4->add($subitem3Id,17276,500);//Knowledge Crystal

            $this->redCraftSubItem3->add($subitem2Id,12545,250);//Orrian Truffle
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19622,1);

            $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19709,250);//Elder Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19711,250);//Hard Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
        //Superior Sigil of Force
        $this->redCraftSubItem2->add($subitem1Id,24615,1);

        //The Hunter
        $this->redCraftSubItem1->add($craftId,29175,1);
    }

    private function addMinstrel() {
        //The Minstrel
        $craftId = $this->redCrafting->add(30688);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Minstrel
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19646,1);

            //Gift of Music
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19630,1);

            $this->redCraftSubItem3->add($subitem2Id,19746,250);//Bolt of Gossamer
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19665,1);//Gift of the Nobleman
            $this->redCraftSubItem4->add($subitem3Id,17274,500);//Seal of Beetletun

            $this->redCraftSubItem3->add($subitem2Id,24522,100);//Opal Orb
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Energy
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19623,1);

            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of Crystalline Dust
            $this->redCraftSubItem3->add($subitem2Id,24276,250);//Pile of Incandescent Dust
            $this->redCraftSubItem3->add($subitem2Id,24275,250);//Pile of Luminous Dust
            $this->redCraftSubItem3->add($subitem2Id,24274,250);//Pile of Radiant Dust

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Energy
            $this->redCraftSubItem2->add($subitem1Id,24607,1);

        //The Bard
        $this->redCraftSubItem1->add($craftId,29168,1);
    }

    private function addDreamer() {
        //The Dreamer
        $craftId = $this->redCrafting->add(30686);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Dreamer
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19660,1);

            //Unicorn Statue
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19628,1);

            $this->redCraftSubItem3->add($subitem2Id,24512,100);//Chrysocola Orb
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19667,1);//Gift of Thorns
            $this->redCraftSubItem4->add($subitem3Id,17273,500);//Deadly Bloom

            $this->redCraftSubItem3->add($subitem2Id,24522,100);//Opal Orb
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19622,1);

            $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19709,250);//Elder Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19711,250);//Hard Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Purity
            $this->redCraftSubItem2->add($subitem1Id,24571,1);

        //The Lover
        $this->redCraftSubItem1->add($craftId,29178,1);
    }

    private function addSunrise() {
        //Sunrise
        $craftId = $this->redCrafting->add(30703);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Sunrise
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19647,1);

            //Gift of Light
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19632,1);

            $this->redCraftSubItem3->add($subitem2Id,24305,100);//Charged Lodestone
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19664,1);//Gift of Ascalon
            $this->redCraftSubItem4->add($subitem3Id,16982,500);//Ascalonian Tear

            $this->redCraftSubItem3->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Strength
            $this->redCraftSubItem2->add($subitem1Id,24562,1);

        //Dawn
         $this->redCraftSubItem1->add($craftId,29169,1);
    }

    private function addRodgort() {
        //Rodgort
        $craftId = $this->redCrafting->add(30700);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Rodgort
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19656,1);

            //Vial of Liquid Flame
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19634,1);

            $this->redCraftSubItem3->add($subitem2Id,24325,100);//Destroyer Lodestone
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19668,1);//Gift of Baelfire
            $this->redCraftSubItem4->add($subitem3Id,17275,500);//Flame Legion Charr Carving

            $this->redCraftSubItem3->add($subitem2Id,12479,250);//Ghost Pepper
            $this->redCraftSubItem3->add($subitem2Id,24315,100);//Molten Lodestone

            //Gift of Wood
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19622,1);

            $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19709,250);//Elder Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19711,250);//Hard Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Fire
            $this->redCraftSubItem2->add($subitem1Id,24548,1);

        //Rodgort's Flame
        $this->redCraftSubItem1->add($craftId,29182,1);
    }

    private function addQuip() {
        //Quip
        $craftId = $this->redCrafting->add(30693);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Quip
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19651,1);

            //Gift of Entertainment
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19635,1);

            $this->redCraftSubItem3->add($subitem2Id,19746,250);//Bolt of Gossamer
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19665,1);//Gift of the Nobleman
            $this->redCraftSubItem4->add($subitem3Id,17274,500);//Seal of Beetletun

            $this->redCraftSubItem3->add($subitem2Id,20000,5);//Evon Gnashblade's Box o' Fun
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19622,1);

            $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19709,250);//Elder Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19711,250);//Hard Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Stamina
            $this->redCraftSubItem2->add($subitem1Id,24592,1);

        //Chaos Gun
        $this->redCraftSubItem1->add($craftId,29174,1);
    }

    private function addMoot() {
        //Moot
        $craftId = $this->redCrafting->add(30692);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Moot
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19650,1);

        //Gift of Entertainment
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19635,1);

        $this->redCraftSubItem3->add($subitem2Id,19746,250);//Bolt of Gossamer
        $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19665,1);//Gift of the Nobleman
        $this->redCraftSubItem4->add($subitem3Id,17274,500);//Seal of Beetletun

        $this->redCraftSubItem3->add($subitem2Id,20000,5);//Evon Gnashblade's Box o' Fun
        $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Energy
            $this->redCraftSubItem2->add($subitem1Id,24607,1);

        //The Energizer
        $this->redCraftSubItem1->add($craftId,29173,1);
    }

    private function addMeteorlogicus() {
        //Meteorlogicus
        $craftId = $this->redCrafting->add(30695);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Meteorlogicus
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19652,1);

            //Gift of Weather
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19637,1);

            $this->redCraftSubItem3->add($subitem2Id,24305,100);//Charged Lodestone
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19671,1);//Gift of Knowledge
            $this->redCraftSubItem4->add($subitem3Id,17276,500);//Knowledge Crystal

            $this->redCraftSubItem3->add($subitem2Id,19732,250);//Hardened Leather Section
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Energy
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19623,1);

            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of Crystalline Dust
            $this->redCraftSubItem3->add($subitem2Id,24276,250);//Pile of Incandescent Dust
            $this->redCraftSubItem3->add($subitem2Id,24275,250);//Pile of Luminous Dust
            $this->redCraftSubItem3->add($subitem2Id,24274,250);//Pile of Radiant Dust

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Air
            $this->redCraftSubItem2->add($subitem1Id,24554,1);

        //Storm
        $this->redCraftSubItem1->add($craftId,29176,1);
    }

    private function addKudzu() {
        //
        $craftId = $this->redCrafting->add(30685);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Kudzu
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19644,1);

            //Gift of Nature
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19642,1);

            $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19667,1);//Gift of Thorns
            $this->redCraftSubItem4->add($subitem3Id,17273,500);//Deadly Bloom

            $this->redCraftSubItem3->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $this->redCraftSubItem3->add($subitem2Id,12128,250);//Omnomberry

            //Gift of Wood
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19622,1);

            $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19709,250);//Elder Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19711,250);//Hard Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Celerity
            $this->redCraftSubItem2->add($subitem1Id,24865,1);

        //Leaf of Kudzu
        $this->redCraftSubItem1->add($craftId,29172,1);
    }

    private function addKraitkin() {
        //Kraitkin
        $craftId = $this->redCrafting->add(49203);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Kraitkin
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19658,1);

            //Eel Statue
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19642,1);

            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19666,1);//Gift of the Forgeman
            $this->redCraftSubItem4->add($subitem3Id,17270,500);//Manifesto of the Moletariate

            $this->redCraftSubItem3->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $this->redCraftSubItem3->add($subitem2Id,19685,150);//Orichalcum Ingot

            //Gift of Energy
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19623,1);

            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of Crystalline Dust
            $this->redCraftSubItem3->add($subitem2Id,24276,250);//Pile of Incandescent Dust
            $this->redCraftSubItem3->add($subitem2Id,24275,250);//Pile of Luminous Dust
            $this->redCraftSubItem3->add($subitem2Id,24274,250);//Pile of Radiant Dust

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Venom
            $this->redCraftSubItem2->add($subitem1Id,24632,1);

        //Venom
        $this->redCraftSubItem1->add($craftId,29183,1);
    }

    private function addKamohoali() {
        //Kamohoali
        $craftId = $this->redCrafting->add(30691);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Kamohoali
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19657,1);

            //Shark Statue
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19641,1);

            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19669,1);//Gift of Zhaitan
            $this->redCraftSubItem4->add($subitem3Id,17272,500);//Shard of Zhaitan

            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,24295,150);//Vial of Powerful Blood

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Agony
            $this->redCraftSubItem2->add($subitem1Id,24612,1);

        //Carcharias
        $this->redCraftSubItem1->add($craftId,29171,1);
    }

    private function addJuggernaught() {
        //Juggernaught
        $craftId = $this->redCrafting->add(49192);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Juggernaut
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19649,1);

            //Vial of Quicksilver
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19633,1);

            $this->redCraftSubItem3->add($subitem2Id,19688,250);//Steel Ingot
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19666,1);//Gift of the Forgeman
            $this->redCraftSubItem4->add($subitem3Id,17270,500);//Manifesto of the Moletariate

            $this->redCraftSubItem3->add($subitem2Id,24502,250);//Silver Doubloon
            $this->redCraftSubItem3->add($subitem2Id,24315,150);//Molten Lodestone

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
        $this->redCraftSubItem2->add($subitem1Id,19676,100);
        //Superior Sigil of Sanctuary
        $this->redCraftSubItem2->add($subitem1Id,24857,1);

        //The Colossus
        $this->redCraftSubItem1->add($craftId,29170,1);
    }

    private function addIncinerator() {
        //Incinerator
        $craftId = $this->redCrafting->add(30687);

            //Gift of Fortune
            $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Incinerator
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19645,1);

            //Vial of Liquid Flame
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19634,1);

            $this->redCraftSubItem3->add($subitem2Id,24325,100);//Destroyer Lodestone
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19668,1);//Gift of Baelfire
            $this->redCraftSubItem4->add($subitem3Id,17275,500);//Flame Legion Charr Carving

            $this->redCraftSubItem3->add($subitem2Id,12479,250);//Ghost Pepper
            $this->redCraftSubItem3->add($subitem2Id,24315,100);//Molten Lodestone

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Fire
            $this->redCraftSubItem2->add($subitem1Id,24548,1);

        //Spark
        $this->redCraftSubItem1->add($craftId,29167,1);
    }

    private function addHowler() {
        //Howler
        $craftId = $this->redCrafting->add(30702);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Howler
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19662,1);

            //Wolf Statue
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19640,1);

            $this->redCraftSubItem3->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19667,1);//Gift of Thorns
            $this->redCraftSubItem4->add($subitem3Id,17273,500);//Deadly Bloom

            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19622,1);

            $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19709,250);//Elder Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19711,250);//Hard Wood Plank
            $this->redCraftSubItem3->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Accuracy
            $this->redCraftSubItem2->add($subitem1Id,24618,1);

        //Howl
        $this->redCraftSubItem1->add($craftId,29184,1);
    }

    private function addFrostfang() {
        //Frostfang
        $craftId = $this->redCrafting->add(30684);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Frostfang
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19625,1);

            //Gift of Ice
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19624,1);

            $this->redCraftSubItem3->add($subitem2Id,24340,1);//Corrupted Lodestone
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19670,1);//Gift of Sanctuary
            $this->redCraftSubItem4->add($subitem3Id,17277,500);//Symbol of Koda

            $this->redCraftSubItem3->add($subitem2Id,24320,100);//Glacial Lonestone
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Ice
            $this->redCraftSubItem2->add($subitem1Id,24555,1);

        //Tooth of Frostfang
        $this->redCraftSubItem1->add($craftId,29166,1);
    }

    private function addFrenzy() {
        //Frenzy
        $craftId = $this->redCrafting->add(49199);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

        //Gift of Magic
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

        $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
        $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
        $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
        $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

        //Gift of Might
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

        $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
        $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
        $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
        $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

        //Glob of Ectoplasm
        $this->redCraftSubItem2->add($subitem1Id,19721,250);
        //Mystic Clover
        $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

        //Bloodstone Shard
        $this->redCraftSubItem2->add($subitem1Id,20797,1);

        //Gift of Battle
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

        $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

        //Gift of Exploration
        $this->redCraftSubItem2->add($subitem1Id,19677,1);

        //Obsidian Shard
        $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of Frenzy
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19659,1);

        //Gift of Water
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19643,1);

        $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19670,1);//Gift of Sanctuary
        $this->redCraftSubItem4->add($subitem3Id,17277,500);//Symbol of Koda

        $this->redCraftSubItem3->add($subitem2Id,24320,100);//Glacial Lonestone
        $this->redCraftSubItem3->add($subitem2Id,24315,100);//Molten Lodestone
        $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

        //Gift of Wood
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19622,1);

        $this->redCraftSubItem3->add($subitem2Id,19712,250);//Ancient Wood Plank
        $this->redCraftSubItem3->add($subitem2Id,19709,250);//Elder Wood Plank
        $this->redCraftSubItem3->add($subitem2Id,19711,250);//Hard Wood Plank
        $this->redCraftSubItem3->add($subitem2Id,19714,250);//Seasoned Wood Plank

        //Icy Runestone
        $this->redCraftSubItem2->add($subitem1Id,19676,100);
        //Superior Sigil of Rage
        $this->redCraftSubItem2->add($subitem1Id,24561,1);

        //Rage
        $this->redCraftSubItem1->add($craftId,29179,1);
    }

    private function addFlameseeker() {
        //The Flameseeker Prophecies
        $craftId = $this->redCrafting->add(30696);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);


        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Flame Seeker Prophices
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19653,1);

            //Gift of History
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19629,1);

            $this->redCraftSubItem3->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19664,1);//Gift of Ascalon
            $this->redCraftSubItem4->add($subitem3Id,16982,500);//Ascalonian Tear

            $this->redCraftSubItem3->add($subitem2Id,24310,100);//Onyx Lodestone
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of Crystalline Dust

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Battle
            $this->redCraftSubItem2->add($subitem1Id,24601,1);

        //The Chosen
        $this->redCraftSubItem1->add($craftId,29177,1);
    }

    private function addBifrost() {
        //Bifrost
        $craftId = $this->redCrafting->add(30698);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

        //Gift of Magic
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

        $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
        $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
        $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
        $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

        //Gift of Might
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

        $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
        $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
        $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
        $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

        //Glob of Ectoplasm
        $this->redCraftSubItem2->add($subitem1Id,19721,250);
        //Mystic Clover
        $this->redCraftSubItem2->add($subitem1Id,19675,77);


        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);

        //Gift of The Bifrost
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19654,1);

        //Gift of Color
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19638,1);

        $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19669,1);//Gift of Zhaitan
        $this->redCraftSubItem4->add($subitem3Id,17272,500);//Shard of Zhaitan

        $this->redCraftSubItem3->add($subitem2Id,24522,100);//Opal Orb
        $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of Crystalline Dust
        $this->redCraftSubItem3->add($subitem2Id,20323,250);//Unidentified Dye

        //Gift of Energy
        $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19623,1);

        $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of Crystalline Dust
        $this->redCraftSubItem3->add($subitem2Id,24276,250);//Pile of Incandescent Dust
        $this->redCraftSubItem3->add($subitem2Id,24275,250);//Pile of Luminous Dust
        $this->redCraftSubItem3->add($subitem2Id,24274,250);//Pile of Radiant Dust

        //Icy Runestone
        $this->redCraftSubItem2->add($subitem1Id,19676,100);
        //Superior Sigil of Nullification
        $this->redCraftSubItem2->add($subitem1Id,24572,1);

        //Gift of The Bifrost
        $this->redCraftSubItem1->add($craftId,29180,1);
    }

    private function addBolt() {
        //Bolt
        $craftId = $this->redCrafting->add(30699);

        //Gift of Bolt
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19655,1);

            //Gift of Lightning
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19639,1);

            $this->redCraftSubItem3->add($subitem2Id,19746,250);//Bolt of Gossamer
            $this->redCraftSubItem3->add($subitem2Id,24305,100);//Charged Lodestone
            $subitem3Id = $this->redCraftSubItem3->add($subitem2Id,19664,1);//Gift of Ascalon
            $this->redCraftSubItem4->add($subitem3Id,16982,500);//Ascalonian Tear
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19621,1);

            $this->redCraftSubItem3->add($subitem2Id,19681,250);//Darksteel Ingot
            $this->redCraftSubItem3->add($subitem2Id,19684,250);//Mithril Ingot
            $this->redCraftSubItem3->add($subitem2Id,19685,250);//Orichalcum Ingot
            $this->redCraftSubItem3->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $this->redCraftSubItem2->add($subitem1Id,19676,100);
            //Superior Sigil of Air
            $this->redCraftSubItem2->add($subitem1Id,24554,1);

        //Gift of Fortune
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19673,1);

            $this->redCraftSubItem3->add($subitem2Id,24300,250);//Elaborate totem
            $this->redCraftSubItem3->add($subitem2Id,24277,250);//Pile of crystalline dust
            $this->redCraftSubItem3->add($subitem2Id,24283,250);//Powerful venom sac
            $this->redCraftSubItem3->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19672,1);

            $this->redCraftSubItem3->add($subitem2Id,24358,250);//Ancient Bone
            $this->redCraftSubItem3->add($subitem2Id,24289,250);//Armored Scale
            $this->redCraftSubItem3->add($subitem2Id,24351,250);//Vicious Claw
            $this->redCraftSubItem3->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $this->redCraftSubItem2->add($subitem1Id,19721,250);
            //Mystic Clover
            $this->redCraftSubItem2->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = $this->redCraftSubItem1->add($craftId,19674,1);

            //Bloodstone Shard
            $this->redCraftSubItem2->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = $this->redCraftSubItem2->add($subitem1Id,19678,1);

            $this->redCraftSubItem3->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $this->redCraftSubItem2->add($subitem1Id,19677,1);

            //Obsidian Shard
            $this->redCraftSubItem2->add($subitem1Id,19925,250);


        //Zap
        $this->redCraftSubItem1->add($craftId,29181,1);
    }

    private function addEternity()
    {
        //Eternity
        $craftId = $this->redCrafting->add(30689);

        //Philosopher's stone
        $this->redCraftSubItem1->add($craftId, 20796, 10);
        //Pile of Crystalline Dust
        $this->redCraftSubItem1->add($craftId, 24277, 5);
        //Sunrise
        $this->redCraftSubItem1->add($craftId, 30703, 1);
        //Twilight
        $this->redCraftSubItem1->add($craftId, 30704, 1);
    }

}