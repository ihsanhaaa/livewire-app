<?php

namespace App\Livewire;

use App\Models\Post as ModelsPost;
use Livewire\Component;
use App\Models\ImageTitle; // Import model ImageTitle
use Livewire\WithFileUploads;

class Post extends Component
{
    use WithFileUploads;

    public $title;
    public $image;
    public $imagePath;

    public function store()
    {
        // Validasi input
        $this->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Ubah sesuai format gambar yang diizinkan dan batas ukuran yang sesuai
        ]);

        // Simpan gambar ke dalam folder storage dan dapatkan path-nya
        $this->imagePath = $this->image->store('images', 'public');

        // Simpan data title dan path gambar ke dalam database
        ModelsPost::create([
            'title' => $this->title,
            'image' => $this->imagePath,
        ]);

        // Reset input fields
        $this->resetForm();

        // Tampilkan pesan sukses
        session()->flash('message', 'Data berhasil disimpan.');
    }

    public function update()
    {
        // Validasi input
        $this->validate([
            'title' => 'required|string|max:255',
        ]);

        // Update data title dalam database berdasarkan ID
        ModelsPost::where('id', $this->titleId)
            ->update([
                'title' => $this->title,
            ]);

        // Tampilkan pesan sukses
        session()->flash('message', 'Data title berhasil diupdate.');
    }

    public function resetForm()
    {
        $this->title = '';
        $this->image = '';
    }

    public function render()
    {
        $posts = ModelsPost::orderBy('id', 'DESC')->get();
        
        return view('livewire.post', compact('posts'))
            ->layout('component.app');
    }
}
