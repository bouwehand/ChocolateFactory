<?php
/**
 * Created by PhpStorm.
 * User: thrynillan
 * Date: 8/16/14
 * Time: 2:19 PM
 */
echo "\n\n DARWIN OPERATIONAL \n\n";

$world = new World();

$worms = $world->createGeneration($world::GENERATION_SIZE);

while($world->timer() < $world::TIME_OF_OF_THE_WORLD) {
    $oldWorms = $world->goForth($worms);
    $fittestWorms = $world->select($oldWorms);
    $this->createGeneration($world::GENERATION_SIZE, $fittestWorms);
}

die(var_dump($world));

