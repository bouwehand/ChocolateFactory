<?php
/**
 * Williams.php
 *
 * Financial, statistical and chaotic tools by Bill Williams
 *
 * @category Chocolate Factory
 * @package forex
 * @author Bas Ouwehand <b.ouwehand@youwe.nl>
 * @date 9/3/15
 *
 */
class Tool_Williams
{
    /**
     * Market Facilitation Index (not! money flow index)
     *
     * @param int|float $high
     * @param int|float $low
     * @param int|float $volume
     * @return float;
     */
    public static function mfi($high, $low, $volume)
    {
        return ($high - $low) / $volume;
    }
}