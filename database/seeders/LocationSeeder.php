<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $india = Country::updateOrCreate(
            ['code' => 'IN'],
            ['name' => 'India', 'sort_order' => 1, 'is_active' => true]
        );

        $statesData = [
            'Andhra Pradesh' => ['AP', ['Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool', 'Tirupati', 'Rajahmundry', 'Kakinada', 'Kadapa', 'Anantapur', 'Eluru', 'Ongole']],
            'Arunachal Pradesh' => ['AR', ['Itanagar', 'Naharlagun', 'Pasighat', 'Tawang']],
            'Assam' => ['AS', ['Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon', 'Tinsukia', 'Tezpur']],
            'Bihar' => ['BR', ['Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Purnia', 'Darbhanga', 'Arrah', 'Begusarai', 'Katihar', 'Munger']],
            'Chhattisgarh' => ['CG', ['Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg', 'Rajnandgaon', 'Jagdalpur']],
            'Goa' => ['GA', ['Panaji', 'Margao', 'Vasco da Gama', 'Mapusa', 'Ponda']],
            'Gujarat' => ['GJ', ['Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar', 'Jamnagar', 'Junagadh', 'Gandhinagar', 'Anand', 'Nadiad', 'Morbi', 'Mehsana', 'Bharuch', 'Vapi', 'Navsari']],
            'Haryana' => ['HR', ['Gurugram', 'Faridabad', 'Panipat', 'Ambala', 'Karnal', 'Sonipat', 'Hisar', 'Rohtak', 'Yamunanagar', 'Panchkula']],
            'Himachal Pradesh' => ['HP', ['Shimla', 'Dharamshala', 'Mandi', 'Solan', 'Kullu', 'Manali', 'Hamirpur']],
            'Jharkhand' => ['JH', ['Ranchi', 'Jamshedpur', 'Dhanbad', 'Bokaro', 'Hazaribagh', 'Deoghar', 'Giridih']],
            'Karnataka' => ['KA', ['Bengaluru', 'Mysuru', 'Mangaluru', 'Hubballi', 'Belagavi', 'Kalaburagi', 'Davangere', 'Ballari', 'Shivamogga', 'Tumkuru', 'Udupi', 'Hassan', 'Mandya']],
            'Kerala' => ['KL', ['Thiruvananthapuram', 'Kochi', 'Kozhikode', 'Thrissur', 'Kollam', 'Kannur', 'Alappuzha', 'Palakkad', 'Kottayam', 'Malappuram']],
            'Madhya Pradesh' => ['MP', ['Bhopal', 'Indore', 'Jabalpur', 'Gwalior', 'Ujjain', 'Sagar', 'Satna', 'Rewa', 'Dewas', 'Ratlam']],
            'Maharashtra' => ['MH', ['Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik', 'Aurangabad', 'Solapur', 'Kolhapur', 'Amravati', 'Navi Mumbai', 'Vasai-Virar', 'Sangli', 'Latur', 'Akola', 'Dhule']],
            'Manipur' => ['MN', ['Imphal', 'Thoubal', 'Bishnupur']],
            'Meghalaya' => ['ML', ['Shillong', 'Tura', 'Jowai']],
            'Mizoram' => ['MZ', ['Aizawl', 'Lunglei', 'Champhai']],
            'Nagaland' => ['NL', ['Kohima', 'Dimapur', 'Mokokchung']],
            'Odisha' => ['OD', ['Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur', 'Sambalpur', 'Puri', 'Balasore', 'Bhadrak']],
            'Punjab' => ['PB', ['Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda', 'Mohali', 'Pathankot', 'Hoshiarpur', 'Moga']],
            'Rajasthan' => ['RJ', ['Jaipur', 'Jodhpur', 'Udaipur', 'Kota', 'Bikaner', 'Ajmer', 'Alwar', 'Bharatpur', 'Sikar', 'Bhilwara', 'Sri Ganganagar']],
            'Sikkim' => ['SK', ['Gangtok', 'Namchi', 'Pelling']],
            'Tamil Nadu' => ['TN', ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli', 'Erode', 'Vellore', 'Thoothukudi', 'Thanjavur', 'Dindigul', 'Tirupur', 'Nagercoil']],
            'Telangana' => ['TS', ['Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar', 'Khammam', 'Mahbubnagar', 'Ramagundam', 'Secunderabad']],
            'Tripura' => ['TR', ['Agartala', 'Udaipur', 'Dharmanagar']],
            'Uttar Pradesh' => ['UP', ['Lucknow', 'Kanpur', 'Agra', 'Varanasi', 'Prayagraj', 'Meerut', 'Ghaziabad', 'Noida', 'Bareilly', 'Aligarh', 'Moradabad', 'Gorakhpur', 'Saharanpur', 'Jhansi', 'Mathura', 'Firozabad', 'Ayodhya']],
            'Uttarakhand' => ['UK', ['Dehradun', 'Haridwar', 'Rishikesh', 'Roorkee', 'Haldwani', 'Rudrapur', 'Nainital', 'Mussoorie']],
            'West Bengal' => ['WB', ['Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri', 'Bardhaman', 'Malda', 'Kharagpur', 'Haldia']],
            'Delhi' => ['DL', ['New Delhi', 'Central Delhi', 'South Delhi', 'North Delhi', 'East Delhi', 'West Delhi', 'Dwarka', 'Rohini', 'Saket']],
            'Chandigarh' => ['CH', ['Chandigarh']],
            'Puducherry' => ['PY', ['Puducherry', 'Karaikal', 'Mahe', 'Yanam']],
            'Jammu & Kashmir' => ['JK', ['Srinagar', 'Jammu', 'Anantnag', 'Baramulla', 'Udhampur']],
            'Ladakh' => ['LA', ['Leh', 'Kargil']],
            'Andaman & Nicobar Islands' => ['AN', ['Port Blair']],
            'Dadra & Nagar Haveli and Daman & Diu' => ['DD', ['Daman', 'Silvassa', 'Diu']],
            'Lakshadweep' => ['LD', ['Kavaratti']],
        ];

        $stateOrder = 1;
        foreach ($statesData as $stateName => [$stateCode, $cities]) {
            $state = State::updateOrCreate(
                ['country_id' => $india->id, 'name' => $stateName],
                ['code' => $stateCode, 'sort_order' => $stateOrder++, 'is_active' => true]
            );

            $cityOrder = 1;
            foreach ($cities as $cityName) {
                City::updateOrCreate(
                    ['state_id' => $state->id, 'name' => $cityName],
                    ['sort_order' => $cityOrder++, 'is_active' => true]
                );
            }
        }
    }
}
