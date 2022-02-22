<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\ChatMessageDocument;

class chatMessageDocumentsInitData extends Seeder
{
    private $chat_message_documents_tb;

    public function __construct()
    {
        $this->chat_message_documents_tb = with(new ChatMessageDocument)->getTable();
    }

    public function run()
    {

        \DB::table($this->chat_message_documents_tb)->insert([
            'chat_message_id'    => 1,
            'user_id'            => 5,
            'filename'           => 'slogan_1.jpg',
            'extension'          => 'jpg',
            'info'               => 'That is motto of our company',
        ]);
        \DB::table($this->chat_message_documents_tb)->insert([
            'chat_message_id'    => 1,
            'user_id'            => 5,
            'filename'           => 'our-services.doc',
            'extension'          => 'doc',
            'info'               => 'Description of our services',
        ]);
        \DB::table($this->chat_message_documents_tb)->insert([
            'chat_message_id'    => 4,
            'user_id'            => 5,
            'filename'           => 'rules-of-our-site.pdf',
            'extension'          => 'pdf',
            'info'               => 'This pdf file contains rules of our site for managers and employees',
        ]);
        \DB::table($this->chat_message_documents_tb)->insert([
            'chat_message_id'    => 6,
            'user_id'            => 2,
            'filename'           => 'our_prices.ods',
            'extension'          => 'ods',
            'info'               => 'This are prices of ours services',
        ]);
        \DB::table($this->chat_message_documents_tb)->insert([
            'chat_message_id'    => 6,
            'user_id'            => 2,
            'filename'           => 'terms.doc',
            'extension'          => 'terms.doc',
            'info'               => 'This are terms of using of our ours services',
        ]);

    }
}
