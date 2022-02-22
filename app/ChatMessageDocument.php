<?php

namespace App;
use DB;
use App\MyAppModel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\funcsTrait;
use Illuminate\Validation\Rule;


class ChatMessageDocument extends MyAppModel
{
    use funcsTrait;
    protected $table      = 'chat_message_documents';
    protected $primaryKey = 'id';
    public $timestamps    = false;

    protected $chatMessageDocumentImagePropsArray = [];
    protected $img_preview_width = 160;
    protected $img_preview_height = 120;

    protected static $uploads_chat_message_documents_dir = 'chat-message-documents/-chat-message-document-';

    protected $fillable = [ 'chat_message_id', 'user_id', 'filename' , 'extension', 'info' ];
    protected static $logAttributes = ['*'];



    protected $casts = [
    ];


//    public function getElasticsearchType(): string
//    {
//        return $this->elasticsearch_type;
//    }

    public function getImgFilenameMaxLength(): int
    {
        return $this->img_filename_max_length;
    }


    public function chatMessage()
    {
        return $this->belongsTo('App\ChatMessage');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }



    public function scopeGetByChatMessageId($query, $chat_message_id= null)
    {
        if (!empty($chat_message_id)) {
            if ( is_array($chat_message_id) ) {
                $query->whereIn(with(new ChatMessageDocument)->getTable().'.chat_message_id', $chat_message_id);
            } else {
                $query->where(with(new ChatMessageDocument)->getTable().'.chat_message_id', $chat_message_id);
            }
        }
        return $query;
    }


    public static function getChatMessageDocumentDir(int $chat_message_document_id): string
    {
        return self::$uploads_chat_message_documents_dir . $chat_message_document_id . '/';
    }

    public static function getChatMessageDocumentImagePath(int $chat_message_document_id, $image): string
    {
        if (empty($image)) {
            return '';
        }
        return self::$uploads_chat_message_documents_dir . $chat_message_document_id . '/' . $image;
    }

    public static function setChatMessageDocumentImageProps(int $chat_message_document_id, string $file = null, bool $skip_non_existing_file = false): array
    {
        if (empty($file) and $skip_non_existing_file) {
            return [];
        }
        $imagesExtensions = config('app.images_extensions');


        $filename_extension = strtolower(with(new Settings)->getFilenameExtension($file));
        $file_was_found = true;

        $dir_path       = self::$uploads_chat_message_documents_dir;
        $file_full_path = $dir_path . $chat_message_document_id . '/' . $file;
//        echo '<pre>$file_full_path::'.print_r($file_full_path,true).'</pre>';
        $file_exists = ( ! empty($file) and Storage::disk('local')->exists('public/' . $file_full_path));
//        echo '<pre>$file_exists::'.print_r($file_exists,true).'</pre>';
//        die("-1 XXZ");

        if ( ! $file_exists) {
            if ($skip_non_existing_file) {
                return [];
            }
            $file_was_found = false;
            $file_full_path = config('app.empty_img_url');
        }

        $extension_filename   = '';
        $fileExtensionsImages = config('app.fileExtensionsImages');
        $file_path = $file_full_path;
        $is_image = false;
        if (in_array($filename_extension, $imagesExtensions)) {
            $is_image = true;
        } else {
            foreach ($fileExtensionsImages as $next_extension => $next_extension_file) {
                if (strtolower($next_extension) == $filename_extension) {
                    $extension_filename = with ( new Settings)->getFilesExtentionDir() . $next_extension_file;
                }
            }
        }

        if ($file_exists) {
            $filePropsArray = [
                'filename'      => $file,
                'chat_message_document_path' => $file_path,
                'chat_message_document_url'  => '/storage/' . $dir_path . $chat_message_document_id . '/' . $file,
                'image_preview_width'       => with(new Settings)->getImgPreviewWidth(),
                'filename_extension'        => $filename_extension,
                'extension_filename'        => $extension_filename,
                'is_image'                  => $is_image
            ];
            $file_full_path = base_path() . '/storage/app/public/' . $file_full_path;
        } else {
            $filePropsArray = [
                'filename'      => $file,
                'chat_message_document_path' => $file_path,
                'chat_message_document_url'  => $file_full_path
            ];
            $file_full_path = base_path() . '/public/' . $file_path;
        }

        if ( ! empty($previewSizeArray['width'])) {
            $filePropsArray['preview_width']  = $previewSizeArray['width'];
            $filePropsArray['preview_height'] = $previewSizeArray['height'];
        }
        $settingsImageImgProps = with(new Settings)->getCFImageProps($file_full_path, $filePropsArray);

        if ( ! $file_was_found) {
            $settingsImageImgProps['file_info'] = 'File "' . $file . '" not found ';
        }

//        echo '<pre>$settingsImageImgProps::'.print_r($settingsImageImgProps,true).'</pre>';
//        die("-1 XXZ");
        return $settingsImageImgProps;

    } // public static function setChatMessageDocumentImageProps(int $chat_message_document_id, string $file = null, bool $skip_non_existing_file = false): array


    public function getImgPreviewWidth(): int
    {
        return (new ChatMessageDocument)->img_preview_width;
    }
    public function getImgPreviewHeight(): int
    {
        return (new ChatMessageDocument)->img_preview_height;
    }


    /* get additional properties of chat_message_document image : path, url, size etc... */
    public function getChatMessageDocumentImagePropsAttribute(): array
    {
        return $this->chatMessageDocumentImagePropsArray;
    }

    /* set additional properties of chat_message_document image : path, url, size etc... */
    public function setChatMessageDocumentImagePropsAttribute(array $chatMessageDocumentImagePropsArray)
    {
        $this->chatMessageDocumentImagePropsArray = $chatMessageDocumentImagePropsArray;
    }

    /* check if provided filename is unique for chat_message_document.name withing 1 chat ( $chat_id ) field */
    public static function getSimilarChatMessageDocumentByFilename( string $filename, int $chat_id, int $id= null, $return_count= false )
    {
        $similarChatMessageDocuments = ChatMessageDocument
            ::join('chat_messages', 'chat_messages.id', '=', 'chat_message_documents.chat_message_id')
            ->join('chats', 'chats.id', '=', 'chat_messages.chat_id')
            ->where( with(new ChatMessageDocument)->getTable().'.filename', $filename )
            ->where( 'chats.id', $chat_id )
            ->get();

        if ( $return_count ) {
            return count($similarChatMessageDocuments);
        }
        if ( empty($similarChatMessageDocuments[0]) ) return false;
        return $similarChatMessageDocuments[0];
    }


    public static function getValidationRulesArray($chat_message_id, $chat_message_document_id = null): array
    {
        $additional_item_value_validation_rule= '';//..'check_chat_message_document_unique_by_filename:'.$chat_message_id.','.( !empty($chat_message_document_id)
//        ?$chat_message_document_id:'');


        $validationRulesArray            = [
            'chat_message_id'      => 'required|exists:'.( with(new ChatMessage)->getTable() ).',id',
            'user_id'              => 'required|exists:'.( with(new User)->getTable() ).',id',
            'filename'             => 'required|max:255|' . $additional_item_value_validation_rule,
            'extension'            => 'required|max:10',
            'info'                 => 'nullable|max:255|',
        ];

        return $validationRulesArray;
    }

}
