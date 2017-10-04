<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class ImageUpload extends Model
{

    public $image;

    public function rules()
    {
        return [
            [['image'], 'required'],
            [['image'], 'file', 'extensions' => 'jpg,png,gif']
        ];


    }

    public function uploadImage(UploadedFile $file, $currentimage)
    {
        $this->image = $file;
        if ($this->validate())
        {
            $this->deleteFile($currentimage);

            $filename = $this->generateFileName($file);

            $file->saveAs($this->pathToFile($filename));
            return $filename;
        }
    }

    public function generateFileName($file){
        return strtolower(md5(uniqid($file->baseName)). '.' . $file->extension);
    }
    public function pathToFile($filename){
        return Yii::getAlias('@web') .'uploads/' . $filename;

    }
    public function deleteFile($file)
    {
        if ($file != null && file_exists($this->pathToFile($file)))
        {
            unlink($this->pathToFile($file));
        }
    }

}


