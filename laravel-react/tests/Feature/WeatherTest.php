<?php


test('Weather page is reachable', function () {
    $this->get('/')
        ->assertStatus(200);
});

test('Weather json route requires city query', function () {
    $this->get('/weather')
        ->assertStatus(400)
        ->assertExactJson(['error' => 'Invalid city provided']);
});

test('Weather json route return weather', function () {
    $this->get('/weather?city=London')
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'city',
                'country',
                'temperature',
                'condition',
                'humidity',
                'windSpeed',
                'lastUpdated',
            ]
        ]);
});


