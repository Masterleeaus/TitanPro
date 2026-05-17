<?php

test('titan validate manifests passes for calling agent module', function () {
    $this->artisan('titan:validate-manifests', ['--module' => 'CallingAgent'])
        ->assertExitCode(0);
});
