<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Facility;
use App\Models\User;
use App\Models\Booking;

class AdminController extends Controller
{

    public function dashboard()
    {
        return view('admin.dashboard');
    }


    public function categories()
    {
        $categories = Category::all();

        return view('admin.categories',compact('categories'));
    }


    public function users()
    {
        $users = User::all();

        return view('admin.users',compact('users'));
    }


    public function sports()
    {
        $sports = Facility::whereHas('category', function($q){
            $q->where('name','like','%sân%');
        })->get();

        return view('admin.sports',compact('sports'));
    }


    public function rooms()
    {
        $rooms = Facility::whereHas('category', function($q){
            $q->where('name','like','%phòng%');
        })->get();

        return view('admin.rooms',compact('rooms'));
    }


    public function hall()
    {
        $halls = Facility::whereHas('category', function($q){
            $q->where('name','like','%hội%');
        })->get();

        return view('admin.hall',compact('halls'));
    }


    public function bookings()
    {
        $bookings = Booking::with(['facility','user'])->latest()->get();

        return view('admin.bookings',compact('bookings'));
    }


    public function stats()
    {
        $totalUsers = User::count();
        $totalBookings = Booking::count();
        $totalFacilities = Facility::count();

        return view('admin.stats',compact(
            'totalUsers',
            'totalBookings',
            'totalFacilities'
        ));
    }

}