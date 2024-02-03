<?php
namespace App\Traits;

use App\Models\Image as ImageModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

trait HasImage{

    public function images(){
        return $this->morphMany(ImageModel::class, 'imagable');
    }

    public function getThumbnailAttribute(){
        $key = $this->id . "_thumbnail";
        return Cache::rememberForever($key, function(){
            $thumbnail = $this->images()->where('type', 'thumbnail')->first();
            if($thumbnail){
                return Storage::url($thumbnail->path);
            }
    
            return  "/images/placeholder.png";
        });
    }
    
    public function removeThumbnail(){
        $thumbnail = $this->images()->where('type', 'thumbnail')->first();
        if($thumbnail){
            $thumbnail->delete();
        }
    }
    
    public function addImage($file, $type = 'thumbnail', $disk = 'public'){
        $image = new ImageModel;
        $image->file_name = $file->getClientOriginalName();
        $image->path = $file->store('images', $disk);
        $image->type = $type;
        $image->file_size = $file->getSize();
        $image->imagable()->associate($this);
        $image->save();
        return $image;
    }

    // public function resize(){
    //     $thumbnail = $this->thumbnail();
    //     if($thumbnail){
    //         resizeImage($thumbnail->path, 250, 250);
    //     }
    // }


}