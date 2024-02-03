<?php

beforeEach(function(){
    $this->withoutExceptionHandling();
    loginUser();
});

it("cannot render category page without permission")
    ->get('/category')
    ->assertStatus(401);