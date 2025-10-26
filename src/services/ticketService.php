<?php
define('TICKETS_FILE', __DIR__ . '/tickets.json');

function getTickets() {
    if (!file_exists(TICKETS_FILE)) file_put_contents(TICKETS_FILE, json_encode([]));
    return json_decode(file_get_contents(TICKETS_FILE), true);
}

function saveTickets($tickets) {
    file_put_contents(TICKETS_FILE, json_encode($tickets, JSON_PRETTY_PRINT));
}

function createTicket($title, $status) {
    $tickets = getTickets();
    $id = time() + rand(0, 1000);
    $tickets[] = ['id' => $id, 'title' => $title, 'status' => $status];
    saveTickets($tickets);
}

function updateTicket($id, $title, $status) {
    $tickets = getTickets();
    foreach ($tickets as &$t) {
        if ($t['id'] == $id) {
            $t['title'] = $title;
            $t['status'] = $status;
        }
    }
    saveTickets($tickets);
}

function deleteTicket($id) {
    $tickets = getTickets();
    $tickets = array_filter($tickets, fn($t) => $t['id'] != $id);
    saveTickets(array_values($tickets));
}
