<?php
/**
 * Created by PhpStorm.
 * User: ikraammoothianpillay1
 * Date: 9/20/15
 * Time: 5:58 PM
 */

namespace KaiApp\Controller;

use Utils\Common;
use KaiApp\RedBO\RedFactory;

class CraftingController extends BaseController
{

    public function get($request, $response, array $args) {
        $craft = RedFactory::GetRedCrafting()->getById($args['id']);
        if (empty($craft))
            return $this->simpleResponse("Missing id",$response,404);

        $craftId = $craft->id;

        $sub1IdsArr = array();
        $sub2IdsArr = array();
        $sub3IdsArr = array();

        $sub1Items = null;
        $sub2Items = null;
        $sub3Items = null;
        $sub4Items = null;

        $sub1Items = RedFactory::GetRedCraftSubItem1()->getAllByCraftId($craftId);
        if (!empty($sub1Items)) {
            $i = 0;
            foreach($sub1Items as $sub1Item) {
                $sub1IdsArr[$i] = $sub1Item->id;
                $i++;
            }

            //Get all sub item level 2
            $sub2Items = RedFactory::GetRedCraftSubItem2()->getAllByCraftIds($sub1IdsArr);
            if (!empty($sub2Items)) {
                $i = 0;
                foreach($sub2Items as $sub2Item) {
                    $sub2IdsArr[$i] = $sub2Item->id;
                    $i++;
                }

                //Get all sub item level 3
                $sub3Items = RedFactory::GetRedCraftSubItem3()->getAllByCraftIds($sub2IdsArr);
                if (!empty($sub3Items)) {
                    $i = 0;
                    foreach($sub3Items as $sub3Item) {
                        $sub3IdsArr[$i] = $sub3Item->id;
                        $i++;
                    }

                    //Get all sub item level 4
                    $sub4Items = RedFactory::GetRedCraftSubItem4()->getAllByCraftIds($sub3IdsArr);
                }
            }
        }

        //Populate object to be encoded
        if (!empty($sub1Items)) {
            foreach($sub1Items as $sub1Item) {
                if(!empty($sub2Items)) {
                    $sub1Item->sub2Item = array();

                    foreach($sub2Items as $sub2Item) {
                        if ($sub1Item->id == $sub2Item->craftsubitem1Id) {
                            if(!empty($sub3Items)) {
                                $sub2Item->sub3Item = array();

                                foreach($sub3Items as $sub3Item) {
                                    if ($sub2Item->id == $sub3Item->craftsubitem2Id) {
                                        if(!empty($sub4Items)) {
                                            $sub3Item->sub4Item = array();

                                            foreach($sub4Items as $sub4Item) {
                                                if ($sub3Item->id == $sub4Item->craftsubitem3Id) {

                                                    array_push($sub3Item->sub4Item,$sub4Item);
                                                }
                                            }
                                        }

                                        array_push($sub2Item->sub3Item,$sub3Item);
                                    }
                                }
                            }

                            array_push($sub1Item->sub2Item,$sub2Item);
                        }
                    }
                }
            }
            $craft->sub1Items = $sub1Items;

            echo json_encode(Common::GenerateResponse(Common::STATUS_SUCCESS,$craft->export()));
        } else {
            echo json_encode(Common::GenerateResponse(Common::STATUS_ERROR, "An error has occured while fetching this craft"));
        }


    }

    public function all($request, $response, array $args) {
        $crafts = RedFactory::GetRedCrafting()->getAll();
        if (empty($crafts))
            $this->simpleResponse("No crafts found",$response, 404);
        else {
            $this->simpleResponse($crafts,$response);
        }
    }

    public function reset($request, $response, array $args) {
        RedFactory::GetRedCraftSubItem4()->wipe();
        RedFactory::GetRedCraftSubItem3()->wipe();
        RedFactory::GetRedCraftSubItem2()->wipe();
        RedFactory::GetRedCraftSubItem1()->wipe();
        RedFactory::GetRedCrafting()->wipe();
        $this->addAll();

        $this->simpleResponse("Legendaries have been reset",$response);
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
        $craftId = RedFactory::GetRedCrafting()->add(30704);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Twilight
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19648,1);

            //Gift of Darkness
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19631,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19664,1);//Gift of Ascalon
            $subitem4Id = RedFactory::GetRedCraftSubItem4()->add($subitem3Id,16982,500);//Ascalonian Tear

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24310,100);//Onyx Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
        //Superior Sigil of Blood
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24570,1);

        //Dusk
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,29185,1);
    }

    private function addPredator() {
        //The Predator
        $craftId = RedFactory::GetRedCrafting()->add(30694);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Predator
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19661,1);

            //Gift of Stealth
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19636,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24310,100);//Onyx Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19671,1);//Gift of Knowledge
            $subitem4Id = RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17276,500);//Knowledge Crystal

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,12545,250);//Orrian Truffle
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19622,1);

            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19709,250);//Elder Wood Plank
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19711,250);//Hard Wood Plank
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
        //Superior Sigil of Force
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24615,1);

        //The Hunter
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,29175,1);
    }

    private function addMinstrel() {
        //The Minstrel
        $craftId = RedFactory::GetRedCrafting()->add(30688);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Minstrel
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19646,1);

            //Gift of Music
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19630,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19746,250);//Bolt of Gossamer
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19665,1);//Gift of the Nobleman
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17274,500);//Seal of Beetletun

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24522,100);//Opal Orb
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Energy
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19623,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of Crystalline Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24276,250);//Pile of Incandescent Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24275,250);//Pile of Luminous Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24274,250);//Pile of Radiant Dust

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Energy
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24607,1);

        //The Bard
        RedFactory::GetRedCraftSubItem1()->add($craftId,29168,1);
    }

    private function addDreamer() {
        //The Dreamer
        $craftId = RedFactory::GetRedCrafting()->add(30686);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Dreamer
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19660,1);

            //Unicorn Statue
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19628,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24512,100);//Chrysocola Orb
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19667,1);//Gift of Thorns
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17273,500);//Deadly Bloom

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24522,100);//Opal Orb
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19622,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19709,250);//Elder Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19711,250);//Hard Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Purity
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24571,1);

        //The Lover
        RedFactory::GetRedCraftSubItem1()->add($craftId,29178,1);
    }

    private function addSunrise() {
        //Sunrise
        $craftId = RedFactory::GetRedCrafting()->add(30703);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Sunrise
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19647,1);

            //Gift of Light
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19632,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24305,100);//Charged Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19664,1);//Gift of Ascalon
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,16982,500);//Ascalonian Tear

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Strength
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24562,1);

        //Dawn
         RedFactory::GetRedCraftSubItem1()->add($craftId,29169,1);
    }

    private function addRodgort() {
        //Rodgort
        $craftId = RedFactory::GetRedCrafting()->add(30700);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Rodgort
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19656,1);

            //Vial of Liquid Flame
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19634,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24325,100);//Destroyer Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19668,1);//Gift of Baelfire
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17275,500);//Flame Legion Charr Carving

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,12479,250);//Ghost Pepper
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24315,100);//Molten Lodestone

            //Gift of Wood
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19622,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19709,250);//Elder Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19711,250);//Hard Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Fire
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24548,1);

        //Rodgort's Flame
        RedFactory::GetRedCraftSubItem1()->add($craftId,29182,1);
    }

    private function addQuip() {
        //Quip
        $craftId = RedFactory::GetRedCrafting()->add(30693);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Quip
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19651,1);

            //Gift of Entertainment
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19635,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19746,250);//Bolt of Gossamer
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19665,1);//Gift of the Nobleman
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17274,500);//Seal of Beetletun

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,20000,5);//Evon Gnashblade's Box o' Fun
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19622,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19709,250);//Elder Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19711,250);//Hard Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Stamina
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24592,1);

        //Chaos Gun
        RedFactory::GetRedCraftSubItem1()->add($craftId,29174,1);
    }

    private function addMoot() {
        //Moot
        $craftId = RedFactory::GetRedCrafting()->add(30692);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Moot
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19650,1);

        //Gift of Entertainment
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19635,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19746,250);//Bolt of Gossamer
        $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19665,1);//Gift of the Nobleman
        RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17274,500);//Seal of Beetletun

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,20000,5);//Evon Gnashblade's Box o' Fun
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Energy
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24607,1);

        //The Energizer
        RedFactory::GetRedCraftSubItem1()->add($craftId,29173,1);
    }

    private function addMeteorlogicus() {
        //Meteorlogicus
        $craftId = RedFactory::GetRedCrafting()->add(30695);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Meteorlogicus
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19652,1);

            //Gift of Weather
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19637,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24305,100);//Charged Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19671,1);//Gift of Knowledge
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17276,500);//Knowledge Crystal

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19732,250);//Hardened Leather Section
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Energy
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19623,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of Crystalline Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24276,250);//Pile of Incandescent Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24275,250);//Pile of Luminous Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24274,250);//Pile of Radiant Dust

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Air
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24554,1);

        //Storm
        RedFactory::GetRedCraftSubItem1()->add($craftId,29176,1);
    }

    private function addKudzu() {
        //
        $craftId = RedFactory::GetRedCrafting()->add(30685);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Kudzu
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19644,1);

            //Gift of Nature
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19642,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19667,1);//Gift of Thorns
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17273,500);//Deadly Bloom

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,12128,250);//Omnomberry

            //Gift of Wood
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19622,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19709,250);//Elder Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19711,250);//Hard Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Celerity
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24865,1);

        //Leaf of Kudzu
        RedFactory::GetRedCraftSubItem1()->add($craftId,29172,1);
    }

    private function addKraitkin() {
        //Kraitkin
        $craftId = RedFactory::GetRedCrafting()->add(49203);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Kraitkin
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19658,1);

            //Eel Statue
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19642,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19666,1);//Gift of the Forgeman
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17270,500);//Manifesto of the Moletariate

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,150);//Orichalcum Ingot

            //Gift of Energy
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19623,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of Crystalline Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24276,250);//Pile of Incandescent Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24275,250);//Pile of Luminous Dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24274,250);//Pile of Radiant Dust

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Venom
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24632,1);

        //Venom
        RedFactory::GetRedCraftSubItem1()->add($craftId,29183,1);
    }

    private function addKamohoali() {
        //Kamohoali
        $craftId = RedFactory::GetRedCrafting()->add(30691);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Kamohoali
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19657,1);

            //Shark Statue
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19641,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19669,1);//Gift of Zhaitan
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17272,500);//Shard of Zhaitan

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,150);//Vial of Powerful Blood

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Agony
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24612,1);

        //Carcharias
        RedFactory::GetRedCraftSubItem1()->add($craftId,29171,1);
    }

    private function addJuggernaught() {
        //Juggernaught
        $craftId = RedFactory::GetRedCrafting()->add(49192);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Juggernaut
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19649,1);

            //Vial of Quicksilver
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19633,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19688,250);//Steel Ingot
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19666,1);//Gift of the Forgeman
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17270,500);//Manifesto of the Moletariate

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24502,250);//Silver Doubloon
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24315,150);//Molten Lodestone

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
        //Superior Sigil of Sanctuary
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24857,1);

        //The Colossus
        RedFactory::GetRedCraftSubItem1()->add($craftId,29170,1);
    }

    private function addIncinerator() {
        //Incinerator
        $craftId = RedFactory::GetRedCrafting()->add(30687);

            //Gift of Fortune
            $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Incinerator
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19645,1);

            //Vial of Liquid Flame
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19634,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24325,100);//Destroyer Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19668,1);//Gift of Baelfire
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17275,500);//Flame Legion Charr Carving

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,12479,250);//Ghost Pepper
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24315,100);//Molten Lodestone

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Fire
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24548,1);

        //Spark
        RedFactory::GetRedCraftSubItem1()->add($craftId,29167,1);
    }

    private function addHowler() {
        //Howler
        $craftId = RedFactory::GetRedCrafting()->add(30702);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Howler
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19662,1);

            //Wolf Statue
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19640,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19667,1);//Gift of Thorns
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17273,500);//Deadly Bloom

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Wood
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19622,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19709,250);//Elder Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19711,250);//Hard Wood Plank
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19714,250);//Seasoned Wood Plank

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Accuracy
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24618,1);

        //Howl
        RedFactory::GetRedCraftSubItem1()->add($craftId,29184,1);
    }

    private function addFrostfang() {
        //Frostfang
        $craftId = RedFactory::GetRedCrafting()->add(30684);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Frostfang
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19625,1);

            //Gift of Ice
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19624,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24340,1);//Corrupted Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19670,1);//Gift of Sanctuary
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17277,500);//Symbol of Koda

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24320,100);//Glacial Lonestone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Ice
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24555,1);

        //Tooth of Frostfang
        RedFactory::GetRedCraftSubItem1()->add($craftId,29166,1);
    }

    private function addFrenzy() {
        //Frenzy
        $craftId = RedFactory::GetRedCrafting()->add(49199);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

        //Gift of Magic
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

        //Gift of Might
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

        //Glob of Ectoplasm
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
        //Mystic Clover
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

        //Bloodstone Shard
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

        //Gift of Battle
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

        //Gift of Exploration
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

        //Obsidian Shard
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of Frenzy
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19659,1);

        //Gift of Water
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19643,1);

        $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19670,1);//Gift of Sanctuary
        RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17277,500);//Symbol of Koda

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24320,100);//Glacial Lonestone
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24315,100);//Molten Lodestone
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

        //Gift of Wood
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19622,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19712,250);//Ancient Wood Plank
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19709,250);//Elder Wood Plank
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19711,250);//Hard Wood Plank
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19714,250);//Seasoned Wood Plank

        //Icy Runestone
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
        //Superior Sigil of Rage
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24561,1);

        //Rage
        RedFactory::GetRedCraftSubItem1()->add($craftId,29179,1);
    }

    private function addFlameseeker() {
        //The Flameseeker Prophecies
        $craftId = RedFactory::GetRedCrafting()->add(30696);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);


        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Flame Seeker Prophices
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19653,1);

            //Gift of History
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19629,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19737,250);//Cured Hardened Leather Square
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19664,1);//Gift of Ascalon
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,16982,500);//Ascalonian Tear

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24310,100);//Onyx Lodestone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of Crystalline Dust

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Battle
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24601,1);

        //The Chosen
        RedFactory::GetRedCraftSubItem1()->add($craftId,29177,1);
    }

    private function addBifrost() {
        //Bifrost
        $craftId = RedFactory::GetRedCrafting()->add(30698);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

        //Gift of Magic
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

        //Gift of Might
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

        //Glob of Ectoplasm
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
        //Mystic Clover
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);


        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);

        //Gift of The Bifrost
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19654,1);

        //Gift of Color
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19638,1);

        $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19669,1);//Gift of Zhaitan
        RedFactory::GetRedCraftSubItem4()->add($subitem3Id,17272,500);//Shard of Zhaitan

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24522,100);//Opal Orb
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of Crystalline Dust
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,20323,250);//Unidentified Dye

        //Gift of Energy
        $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19623,1);

        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of Crystalline Dust
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24276,250);//Pile of Incandescent Dust
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24275,250);//Pile of Luminous Dust
        RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24274,250);//Pile of Radiant Dust

        //Icy Runestone
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
        //Superior Sigil of Nullification
        RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24572,1);

        //Gift of The Bifrost
        RedFactory::GetRedCraftSubItem1()->add($craftId,29180,1);
    }

    private function addBolt() {
        //Bolt
        $craftId = RedFactory::GetRedCrafting()->add(30699);

        //Gift of Bolt
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19655,1);

            //Gift of Lightning
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19639,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19746,250);//Bolt of Gossamer
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24305,100);//Charged Lodestone
            $subitem3Id = RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19664,1);//Gift of Ascalon
            RedFactory::GetRedCraftSubItem4()->add($subitem3Id,16982,500);//Ascalonian Tear
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot

            //Gift of Metal
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19621,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19681,250);//Darksteel Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19684,250);//Mithril Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19685,250);//Orichalcum Ingot
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,19686,250);//Platinum Ingot

            //Icy Runestone
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19676,100);
            //Superior Sigil of Air
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,24554,1);

        //Gift of Fortune
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19626,1);

            //Gift of Magic
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19673,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24300,250);//Elaborate totem
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24277,250);//Pile of crystalline dust
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24283,250);//Powerful venom sac
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24295,250);//Vial of powerful blood

            //Gift of Might
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19672,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24358,250);//Ancient Bone
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24289,250);//Armored Scale
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24351,250);//Vicious Claw
            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,24357,250);//Vicious Fang

            //Glob of Ectoplasm
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19721,250);
            //Mystic Clover
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19675,77);

        //Gift of Mastery
        $subitem1Id = RedFactory::GetRedCraftSubItem1()->add($craftId,19674,1);

            //Bloodstone Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,20797,1);

            //Gift of Battle
            $subitem2Id = RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19678,1);

            RedFactory::GetRedCraftSubItem3()->add($subitem2Id,35510,500);//Badge of Honor

            //Gift of Exploration
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19677,1);

            //Obsidian Shard
            RedFactory::GetRedCraftSubItem2()->add($subitem1Id,19925,250);


        //Zap
        RedFactory::GetRedCraftSubItem1()->add($craftId,29181,1);
    }

    private function addEternity()
    {
        //Eternity
        $craftId = RedFactory::GetRedCrafting()->add(30689);

        //Philosopher's stone
        RedFactory::GetRedCraftSubItem1()->add($craftId, 20796, 10);
        //Pile of Crystalline Dust
        RedFactory::GetRedCraftSubItem1()->add($craftId, 24277, 5);
        //Sunrise
        RedFactory::GetRedCraftSubItem1()->add($craftId, 30703, 1);
        //Twilight
        RedFactory::GetRedCraftSubItem1()->add($craftId, 30704, 1);
    }

}