---
description: Refactor manual Device::with(...) query blocks in controllers to use CommonController::getInstance()->getMatrixByPoNo($params, $with), with no behavior change.
agent: agent
tools: ['codebase', 'search', 'editFiles']
---

# Task
Replace repetitive inline `Device::with(...)->where(...)->first()` / `->get()` blocks in the target controllers with the existing helper `CommonController::getInstance()->getMatrixByPoNo($params, $with)`. Behavior must not change.

# Reference files (read fully before editing)
- #file:app/Http/Controllers/CommonController.php (contains `getMatrixByPoNo` and `ensurePoNoAttribute`; read-only)
- #file:.github/copilot-instructions.md
- Target controllers: `app\Http\Controllers\DefectEscalationController.php,app\Http\Controllers\ProductionRuncardController.php,app\Http\Controllers\ProductionRuncardController_rev_old.php,app\Http\Controllers\ProductionRuncardController_rev.php

`

# Hard constraints
- Do not modify `getMatrixByPoNo` or `ensurePoNoAttribute` (signature, logic, or return type).
- Do not edit files outside the target controllers.
- Do not change routes, response shapes, validation, or unrelated code.
- No unused imports or variables after the refactor. Remove the `Device` import only if no usage remains in that file.

# Steps
1. Read `getMatrixByPoNo` and note: accepted `$params` keys, how `$with` is applied, and whether it returns a single model, a collection, or either.
2. Search the target controllers for manual `Device::with(...)` calls that use relationship closures (e.g. `process_flow`, or `material_process` and `material_process.material_details` with conditional filters on `tbl_wbs`, `status`, `item`).
3. For each call site, replace it only if all of these hold:
   - The lookup key and `where` conditions match what the helper already does.
   - The result type (single vs collection) matches what the caller expects.
   - No extra constraints (ordering, limits, extra `where` columns) would be lost.
4. Move the eager-load array and closures unchanged into the second argument:

```php
// Before
$device = Device::with([
    'process_flow',
    'material_process' => fn ($q) => $q->where('status', $status),
])->where('po_no', $poNo)->first();

// After
$device = CommonController::getInstance()->getMatrixByPoNo(
    ['po_no' => $poNo],
    [
        'process_flow',
        'material_process' => fn ($q) => $q->where('status', $status),
    ]
);
```

5. If a call site does not meet every condition in step 3, leave it unchanged and list it in the output with the reason.

# Output
1. The edited controller files.
2. A table of every `Device::with(...)` call site found: file, method, line, and Refactored / Skipped (with reason).
3. A short note on any difference in behavior risk you identified.

# Acceptance checks
- `CommonController.php` has no diff.
- Every refactored call returns the same data type and relations as before.
- Closures and filters (`tbl_wbs`, `status`, `item`, etc.) are carried over exactly.
<!-- - Lint/tests pass: `<PLACEHOLDER: lint and test commands>`. -->
