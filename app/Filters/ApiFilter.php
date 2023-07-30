<?php

namespace App\Filters;

use Illuminate\Http\Request;

class ApiFilter {
    protected $safeParam = [];

    protected $operationMap = [];

    public function transform(Request $request) {
        $olqQuery = [];

        foreach ($this->safeParam as $parm => $operations) {
            $query = $request->query($parm);
            if (!isset($query)) {
                continue;
            }

            foreach ($operations as $operation) {
                if (isset($query[$operation])) {
                    $olqQuery[] = [$parm, $this->operationMap[$operation], $query[$operation]];
                }
            }
        }
        return $olqQuery;
    }
}
