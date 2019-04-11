<?php

namespace webignition\SfsResultAnalyser;

use webignition\SfsResultInterfaces\ResultInterface;

class Analyser
{
    const NO_TRUST_THRESHOLD = 0.2;

    /**
     * Calculates a trustworthiness score, a floating-point number between 0 (untrustworthy) and 1 (trustworthy)
     *
     * @param ResultInterface $result
     *
     * @return float
     */
    public function calculateTrustworthiness(ResultInterface $result): float
    {
        if (false === $result->getAppears()) {
            return (float) 1;
        }

        if ($result->isBlacklisted()) {
            return (float) 0;
        }

        $confidence = $result->getConfidence();
        if (null === $confidence) {
            return (float) 1;
        }

        $zeroToOneConfidence = $confidence / 100;

        return 1 - $zeroToOneConfidence;
    }

    public function isUntrustworthy(ResultInterface $result, float $noTrustThreshold = self::NO_TRUST_THRESHOLD): bool
    {
        return $this->calculateTrustworthiness($result) < $noTrustThreshold;
    }
}
