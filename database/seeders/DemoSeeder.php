<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\AssessmentOption;
use App\Models\AssessmentQuestion;
use App\Models\Certificate;
use App\Models\ContactMessage;
use App\Models\Consultation;
use App\Models\Feedback;
use App\Models\LawyerAvailability;
use App\Models\LegalArea;
use App\Models\Module;
use App\Models\ModuleProgress;
use App\Models\Program;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Users ──────────────────────────────────────────────────────

        User::create([
            'name'      => 'Platform Admin',
            'email'     => 'admin@ainsheba.test',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $lawyers = $this->createLawyers();
        [$lawyer1, $lawyer2, $lawyer3, $lawyer4] = $lawyers;

        $this->createAvailability($lawyers);

        $citizens = $this->createCitizens();

        // ── 2. Programs ───────────────────────────────────────────────────

        $areas = LegalArea::pluck('id', 'slug');

        $programs = $this->createPrograms($areas, $lawyers);
        [$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8] = $programs;

        // ── 3. Assessments (programs 2 and 5, module 2 of each) ──────────

        $this->createAssessment(
            $p2->modules->where('order_index', 2)->first(),
            'Employment Contracts Knowledge Check',
            [
                [
                    'q'    => 'What is the minimum wage for workers in Bangladesh\'s Ready-Made Garment sector?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => '৳8,000 per month', 'correct' => true],
                        ['text' => '৳5,000 per month', 'correct' => false],
                        ['text' => '৳10,000 per month', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'Which of the following must legally be included in an employment contract?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Job title, salary, and working hours', 'correct' => true],
                        ['text' => 'Employee\'s personal hobbies and interests', 'correct' => false],
                        ['text' => 'Employer\'s home address', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'What is the standard probation period allowed for skilled workers under Bangladesh Labor Act 2006?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Three months', 'correct' => true],
                        ['text' => 'Twelve months for all workers', 'correct' => false],
                        ['text' => 'No probation is legally permitted', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'If an employer terminates an employee without notice, what compensation must they provide?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Pay equivalent to the contractual notice period', 'correct' => true],
                        ['text' => 'Only a formal written apology', 'correct' => false],
                        ['text' => 'Nothing if there is no written contract', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'Under which law are employment contracts primarily governed in Bangladesh?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Bangladesh Labor Act 2006', 'correct' => true],
                        ['text' => 'Contract Act 1872', 'correct' => false],
                        ['text' => 'Civil Procedure Code 1908', 'correct' => false],
                    ],
                ],
            ]
        );

        $this->createAssessment(
            $p5->modules->where('order_index', 2)->first(),
            'Domestic Violence Laws Knowledge Check',
            [
                [
                    'q'    => 'Under which Act is domestic violence legally addressed in Bangladesh?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Domestic Violence (Prevention and Protection) Act 2010', 'correct' => true],
                        ['text' => 'Women and Children Repression Prevention Act 2000', 'correct' => false],
                        ['text' => 'Family Courts Ordinance 1985', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'A protection order under the Domestic Violence Act 2010 is obtained from which authority?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Magistrate Court', 'correct' => true],
                        ['text' => 'High Court Division only', 'correct' => false],
                        ['text' => 'Local government office (Union Parishad)', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'Which of the following is considered domestic violence under the 2010 Act?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Physical, emotional, sexual, and economic abuse', 'correct' => true],
                        ['text' => 'Only physical abuse resulting in injury', 'correct' => false],
                        ['text' => 'Only physical and sexual abuse', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'What is the maximum imprisonment for violating a protection order issued under the 2010 Act?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => '2 years', 'correct' => true],
                        ['text' => '6 months', 'correct' => false],
                        ['text' => '5 years', 'correct' => false],
                    ],
                ],
                [
                    'q'    => 'Who can assist a survivor in applying for a protection order under the Act?',
                    'type' => 'single',
                    'pts'  => 1,
                    'opts' => [
                        ['text' => 'Any relative, neighbor, or registered service provider', 'correct' => true],
                        ['text' => 'Only a licensed police officer', 'correct' => false],
                        ['text' => 'Only the victim herself', 'correct' => false],
                    ],
                ],
            ]
        );

        // ── 4. Registrations, ModuleProgress & Certificates ──────────────

        [$c1, $c2, $c3, $c4, $c5, $c6, $c7, $c8, $c9, $c10, $c11, $c12] = $citizens;

        // Completed (5): all modules done, certificate issued
        $this->completeRegistration($c1, $p1, '2026-05-10', '2026-05-25');
        $this->completeRegistration($c2, $p1, '2026-05-12', '2026-05-28');
        $this->completeRegistration($c3, $p2, '2026-05-15', '2026-06-02');
        $this->completeRegistration($c4, $p3, '2026-05-18', '2026-06-05');
        $this->completeRegistration($c5, $p5, '2026-05-20', '2026-06-10');

        // In-progress (8): partial module progress
        $this->inProgressRegistration($c6,  $p2, '2026-06-01', 2);
        $this->inProgressRegistration($c7,  $p4, '2026-06-03', 1);
        $this->inProgressRegistration($c8,  $p5, '2026-06-05', 3);
        $this->inProgressRegistration($c9,  $p6, '2026-06-08', 2);
        $this->inProgressRegistration($c1,  $p4, '2026-06-10', 1);
        $this->inProgressRegistration($c2,  $p3, '2026-06-12', 2);
        $this->inProgressRegistration($c10, $p1, '2026-06-14', 3);
        $this->inProgressRegistration($c11, $p7, '2026-06-16', 1);

        // No-progress (7)
        $this->bareRegistration($c12, $p2, '2026-06-18');
        $this->bareRegistration($c3,  $p4, '2026-06-19');
        $this->bareRegistration($c4,  $p6, '2026-06-20');
        $this->bareRegistration($c6,  $p7, '2026-06-21');
        $this->bareRegistration($c7,  $p8, '2026-06-22');
        $this->bareRegistration($c8,  $p1, '2026-06-23');
        $this->bareRegistration($c9,  $p3, '2026-06-24');

        // ── 5. Feedback (12 entries) ──────────────────────────────────────

        $feedbacks = [
            [$c1,  $p1, 5, 'Excellent program! The explanations of fundamental rights were very clear and practical. I now feel confident knowing my constitutional rights.'],
            [$c2,  $p1, 4, 'Very informative content. The section on enforcement of rights was especially helpful. Would recommend to everyone.'],
            [$c3,  $p2, 5, 'This program changed how I understand my rights as a worker. Every employee in Bangladesh should take this course.'],
            [$c4,  $p3, 4, 'Good overview of tenant rights. The module on rental agreements helped me avoid a dispute with my landlord.'],
            [$c5,  $p5, 5, 'Advocate Nasrin explains complex laws in a very accessible way. The domestic violence protection section was eye-opening.'],
            [$c6,  $p2, 3, 'Decent content but could use more real-life case examples. The assessment was a bit tricky.'],
            [$c7,  $p4, 4, 'Very useful for understanding consumer rights. I especially liked the section on filing complaints with consumer courts.'],
            [$c8,  $p5, 4, 'The workplace harassment module was very important and well-presented. A must-watch for working women.'],
            [$c1,  $p4, 3, 'The content is good but I expected more practical guidance on the complaint filing process.'],
            [$c2,  $p3, 5, 'One of the best legal awareness programs I have taken. The common disputes module was highly relevant.'],
            [$c10, $p1, 4, 'Well-structured program. The quizzes at the end helped reinforce the learning. Great job overall.'],
            [$c11, $p7, 5, 'The workplace safety content is very practical. It motivated me to check safety conditions at my own workplace.'],
        ];

        foreach ($feedbacks as [$citizen, $program, $rating, $comment]) {
            Feedback::create([
                'citizen_id' => $citizen->id,
                'program_id' => $program->id,
                'rating'     => $rating,
                'comment'    => $comment,
            ]);
        }

        // ── 6. Consultations (6) ──────────────────────────────────────────

        Consultation::create([
            'citizen_id'    => $c1->id,
            'lawyer_id'     => $lawyer1->id,
            'booked_date'   => '2026-07-07',
            'time_slot'     => '10:00 - 11:00',
            'status'        => 'confirmed',
            'fee'           => 500,
            'citizen_notes' => 'I need advice on a wrongful termination case at my garment factory job.',
        ]);

        Consultation::create([
            'citizen_id'    => $c2->id,
            'lawyer_id'     => $lawyer2->id,
            'booked_date'   => '2026-07-08',
            'time_slot'     => '14:00 - 15:00',
            'status'        => 'confirmed',
            'fee'           => 300,
            'citizen_notes' => 'My landlord is threatening eviction without any legal notice. I need urgent guidance.',
        ]);

        Consultation::create([
            'citizen_id'    => $c3->id,
            'lawyer_id'     => $lawyer1->id,
            'booked_date'   => '2026-07-14',
            'time_slot'     => '10:00 - 11:00',
            'status'        => 'pending',
            'fee'           => 500,
            'citizen_notes' => 'I want to understand my rights regarding overtime pay and holiday benefits.',
        ]);

        Consultation::create([
            'citizen_id'    => $c4->id,
            'lawyer_id'     => $lawyer2->id,
            'booked_date'   => '2026-07-13',
            'time_slot'     => '09:00 - 10:00',
            'status'        => 'pending',
            'fee'           => 300,
            'citizen_notes' => 'I purchased a defective refrigerator and the company is refusing to replace it.',
        ]);

        Consultation::create([
            'citizen_id'    => $c5->id,
            'lawyer_id'     => $lawyer1->id,
            'booked_date'   => '2026-06-16',
            'time_slot'     => '14:00 - 15:00',
            'status'        => 'completed',
            'fee'           => 500,
            'citizen_notes' => 'Seeking advice on inheritance rights after the passing of my father.',
            'lawyer_response' => 'Under the Muslim Personal Law (Shariat) Application Act 1937, daughters are entitled to half the share of a son from the father\'s estate. I advise obtaining the death certificate and initiating a succession certificate application at the Civil Court. I will prepare the necessary documents for you.',
        ]);

        Consultation::create([
            'citizen_id'    => $c6->id,
            'lawyer_id'     => $lawyer3->id,
            'booked_date'   => '2026-06-25',
            'time_slot'     => '11:00 - 12:00',
            'status'        => 'cancelled',
            'fee'           => 400,
            'citizen_notes' => 'Wanted to discuss environmental violations by a factory near my home.',
        ]);

        // ── 7. Contact Messages (3) ───────────────────────────────────────

        ContactMessage::create([
            'name'    => 'Rahim Uddin',
            'email'   => 'rahim.uddin@example.com',
            'subject' => 'Question about labor law program enrollment',
            'message' => 'I am a factory worker and want to enroll in the labor law program. Can you please guide me on how to get started? I have limited internet access but I am very eager to learn about my rights.',
        ]);

        ContactMessage::create([
            'name'    => 'Shakila Begum',
            'email'   => 'shakila.b@example.com',
            'subject' => 'Tenant rights — need urgent help',
            'message' => 'My landlord has raised the rent by 50% without any prior notice and is threatening to change the locks. I have been living in this flat for 6 years. I need to know what legal steps I can take to protect myself and my family.',
        ]);

        ContactMessage::create([
            'name'    => 'Mahfuz Ahmed',
            'email'   => 'mahfuz.a@example.com',
            'subject' => 'Partnership inquiry for legal awareness workshop',
            'message' => 'I run a small NGO in Sylhet that works with marginalized communities. We would like to partner with Ain Sheba to conduct legal awareness workshops in our area. Please let us know who to contact for collaboration opportunities.',
        ]);
    }

    // ── Private helpers ───────────────────────────────────────────────────

    private function createLawyers(): array
    {
        $lawyer1 = User::create([
            'name'             => 'Advocate Rahim Chowdhury',
            'email'            => 'lawyer1@ainsheba.test',
            'password'         => Hash::make('password'),
            'role'             => 'lawyer',
            'is_active'        => true,
            'bio'              => 'Constitutional and labor law expert with 12 years of practice in the Bangladesh Supreme Court. Former assistant professor of Law at Dhaka University. Passionate about workers\' rights and access to justice.',
            'consultation_fee' => 500,
            'phone'            => '01711-000001',
        ]);

        $lawyer2 = User::create([
            'name'             => 'Advocate Nasrin Sultana',
            'email'            => 'lawyer2@ainsheba.test',
            'password'         => Hash::make('password'),
            'role'             => 'lawyer',
            'is_active'        => true,
            'bio'              => 'Specialist in women\'s rights, tenant law, and family law with over 8 years of active practice. Active member of Bangladesh National Women Lawyers Association. Known for providing pro bono assistance to survivors of domestic violence.',
            'consultation_fee' => 300,
            'phone'            => '01711-000002',
        ]);

        $lawyer3 = User::create([
            'name'             => 'Advocate Tariqul Hassan',
            'email'            => 'lawyer3@ainsheba.test',
            'password'         => Hash::make('password'),
            'role'             => 'lawyer',
            'is_active'        => true,
            'bio'              => 'Environmental law and corporate compliance specialist with a decade of experience. LLM from University of Dhaka, certified in international environmental law. Regularly advises businesses and NGOs on environmental regulatory compliance.',
            'consultation_fee' => 450,
            'phone'            => '01711-000003',
        ]);

        $lawyer4 = User::create([
            'name'             => 'Advocate Shahida Khanam',
            'email'            => 'lawyer4@ainsheba.test',
            'password'         => Hash::make('password'),
            'role'             => 'lawyer',
            'is_active'        => true,
            'bio'              => 'Land law and property rights expert practicing for over 15 years. Handled over 500 land dispute cases across Dhaka, Chittagong, and Sylhet. Specializes in land registration, mutation, and acquisition matters under Bangladesh law.',
            'consultation_fee' => 400,
            'phone'            => '01711-000004',
        ]);

        return [$lawyer1, $lawyer2, $lawyer3, $lawyer4];
    }

    private function createAvailability(array $lawyers): void
    {
        [$l1, $l2, $l3, $l4] = $lawyers;

        $slots = [
            [$l1->id, 'Monday',    '10:00', '13:00'],
            [$l1->id, 'Wednesday', '10:00', '13:00'],
            [$l1->id, 'Friday',    '14:00', '17:00'],

            [$l2->id, 'Sunday',    '09:00', '12:00'],
            [$l2->id, 'Tuesday',   '14:00', '17:00'],
            [$l2->id, 'Thursday',  '14:00', '17:00'],

            [$l3->id, 'Monday',    '14:00', '17:00'],
            [$l3->id, 'Wednesday', '14:00', '17:00'],

            [$l4->id, 'Sunday',    '10:00', '13:00'],
            [$l4->id, 'Tuesday',   '10:00', '13:00'],
            [$l4->id, 'Thursday',  '10:00', '13:00'],
        ];

        foreach ($slots as [$lawyerId, $day, $start, $end]) {
            LawyerAvailability::create([
                'lawyer_id'  => $lawyerId,
                'day_of_week' => $day,
                'start_time' => $start,
                'end_time'   => $end,
                'is_active'  => true,
            ]);
        }
    }

    private function createCitizens(): array
    {
        $data = [
            ['Karim Hossain',     'citizen1@ainsheba.test'],
            ['Fatema Begum',      'citizen2@ainsheba.test'],
            ['Rafiqul Islam',     'citizen3@ainsheba.test'],
            ['Sumaiya Akter',     'citizen4@ainsheba.test'],
            ['Jahangir Alam',     'citizen5@ainsheba.test'],
            ['Mehedi Hasan',      'citizen6@ainsheba.test'],
            ['Roksana Parvin',    'citizen7@ainsheba.test'],
            ['Shahjahan Mia',     'citizen8@ainsheba.test'],
            ['Nargis Khatun',     'citizen9@ainsheba.test'],
            ['Abul Kalam Azad',   'citizen10@ainsheba.test'],
            ['Shirin Akter',      'citizen11@ainsheba.test'],
            ['Mizanur Rahman',    'citizen12@ainsheba.test'],
        ];

        $citizens = [];
        foreach ($data as [$name, $email]) {
            $citizens[] = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => Hash::make('password'),
                'role'      => 'citizen',
                'is_active' => true,
            ]);
        }

        return $citizens;
    }

    private function createPrograms(array|\Illuminate\Support\Collection $areas, array $lawyers): array
    {
        [$l1, $l2, $l3, $l4] = $lawyers;

        $p1 = $this->createProgram(
            $l1,
            $areas['constitutional-rights'],
            'Know Your Constitutional Rights',
            'A foundational course covering the fundamental rights guaranteed under the Constitution of Bangladesh. Learn how these rights protect you in daily life and what to do when they are violated.',
            'beginner', 'Bengali',
            [
                [1, 'Introduction to the Constitution of Bangladesh', 'The Constitution of Bangladesh, adopted in 1972, is the supreme law of the land. It establishes the framework of government, defines fundamental state policies, and guarantees basic rights to all citizens. Understanding its structure is the first step to knowing your legal protections.', 20, true],
                [2, 'Fundamental Rights of Citizens', 'Part III of the Constitution (Articles 26–47) enshrines fundamental rights including the right to equality, freedom of speech, freedom of movement, and right to life. These rights are justiciable, meaning a citizen can approach the High Court if any of these rights are violated by state action.', 25, true],
                [3, 'Right to Equality and Non-Discrimination', 'Article 27 guarantees all citizens are equal before the law. Article 28 prohibits discrimination based on religion, race, caste, sex, or place of birth. Knowing these provisions helps citizens challenge unlawful discrimination in employment, public services, and government actions.', 25, false],
                [4, 'Enforcement of Fundamental Rights', 'Article 44 gives every citizen the right to move the High Court Division for enforcement of fundamental rights. The writ jurisdiction allows courts to issue orders such as mandamus, prohibition, certiorari, and habeas corpus. This module explains when and how to exercise this powerful legal remedy.', 30, false],
            ]
        );

        $p2 = $this->createProgram(
            $l1,
            $areas['labor-law'],
            'Understanding Labor Laws in Bangladesh',
            'An intermediate course covering the Bangladesh Labor Act 2006 and related regulations. Essential knowledge for workers, HR professionals, and employers navigating employment contracts, wages, working conditions, and dispute resolution.',
            'intermediate', 'Bengali',
            [
                [1, 'Overview of the Bangladesh Labor Act 2006', 'The Bangladesh Labor Act 2006 is the primary legislation governing employment relationships in Bangladesh. It covers over 4 million workers across industries. This module introduces the scope, key definitions, and the bodies responsible for enforcement including the Department of Inspection for Factories and Establishments.', 25, true],
                [2, 'Employment Contracts and Minimum Wage', 'Every employment relationship must be documented in a contract specifying duties, salary, working hours, and termination conditions. The Minimum Wage Board sets sector-specific minimum wages, most recently ৳8,000 per month for garment workers. This module covers contract formation, mandatory clauses, and wage payment obligations.', 30, true],
                [3, 'Working Hours, Leave and Holidays', 'The Labor Act limits working hours to 8 hours per day and 48 hours per week, with mandatory overtime pay for additional hours. Workers are entitled to 10 days of annual leave, 14 days of sick leave, and 11 days of festival holidays per year. Violations carry specific penalties for employers.', 25, false],
                [4, 'Workers\' Compensation and Benefits', 'Injured workers are entitled to compensation under the Employees Compensation Act. Additional benefits include provident fund contributions, gratuity on completion of 5 years of service, and maternity benefits for women workers. This module explains eligibility, calculation, and claim procedures.', 30, false],
                [5, 'Dispute Resolution and Labor Courts', 'Labor disputes are resolved through a tiered system: conciliation by the Conciliator, arbitration, and finally the Labor Court. Workers can also file complaints with the Department of Labor. This module explains the entire process from filing a complaint to enforcing a Labor Court award.', 35, false],
            ]
        );

        $p3 = $this->createProgram(
            $l2,
            $areas['tenant-rights'],
            'Tenant Rights and Rental Agreements',
            'A practical guide for tenants in Bangladesh covering rental agreements, tenant rights under the law, common disputes, and legal remedies. Learn how to protect yourself from arbitrary rent increases and unlawful evictions.',
            'beginner', 'Bengali',
            [
                [1, 'Understanding Rental Agreements', 'A rental agreement (ijara) is a legally binding contract between landlord and tenant. It must specify the rent amount, duration, advance payment terms, and conditions for renewal or termination. In Bangladesh, rental agreements can be oral or written, but written agreements are strongly recommended for legal protection.', 20, true],
                [2, 'Rights and Responsibilities of Tenants', 'Tenants have the right to peaceful possession, access to basic utilities, and a habitable living space. The landlord cannot enter the property without prior notice. Tenants must pay rent on time, maintain the property, and give proper notice before vacating. Understanding both sides prevents most disputes.', 25, true],
                [3, 'Common Disputes and How to Resolve Them', 'The most frequent disputes involve illegal rent hikes, withholding of advance money, unlawful eviction attempts, and utility disconnections. The Rent Control Ordinance of 1991 regulates rent increases in designated areas. This module covers mediation, negotiation, and when to escalate to the Rent Controller.', 25, false],
                [4, 'Legal Remedies for Tenants', 'When informal resolution fails, tenants can approach the Rent Control Court to challenge illegal evictions or unlawful rent increases. Alternatively, a civil suit in the Munsif Court may be filed for monetary claims. This module walks through filing procedures, required documents, and typical timelines.', 30, false],
            ]
        );

        $p4 = $this->createProgram(
            $l2,
            $areas['consumer-rights'],
            'Consumer Protection Laws',
            'An English-language introduction to consumer protection in Bangladesh. Covers the Consumer Rights Protection Act 2009, product liability, complaint mechanisms, and online consumer rights in the digital age.',
            'beginner', 'English',
            [
                [1, 'Consumer Rights: An Overview', 'The Consumer Rights Protection Act 2009 guarantees eight fundamental consumer rights in Bangladesh including the right to safety, right to information, right to choose, and right to redress. The Directorate of National Consumer Rights Protection (DNCRP) is the primary enforcement body. This module introduces the legal framework and key definitions.', 20, true],
                [2, 'Defective Products and Liability', 'Sellers and manufacturers are liable for defective goods under both contract law and the Consumer Rights Act. A product is defective if it is unsafe, does not perform as promised, or is adulterated. Consumers can demand replacement, refund, or compensation without proving negligence when a product is found to be defective.', 25, true],
                [3, 'Consumer Courts and Filing Complaints', 'Complaints can be filed at the DNCRP district office, mobile court, or directly with the Magistrate Court. The DNCRP can impose fines up to BDT 2 lakh and imprison violators for up to 2 years. This module provides a step-by-step guide on preparing and submitting a consumer complaint effectively.', 30, false],
                [4, 'Online Shopping and Digital Consumer Rights', 'E-commerce in Bangladesh is governed by the Digital Commerce Policy 2018 and related guidelines from the Bangladesh Bank and Commerce Ministry. Key rights include the right to refunds for undelivered goods, accurate product descriptions, and secure payment processing. Learn how to protect yourself when shopping on platforms like Chaldal, Shajgoj, and Daraz.', 25, false],
            ]
        );

        $p5 = $this->createProgram(
            $l2,
            $areas['womens-rights'],
            'Women\'s Rights and Legal Protection',
            'A comprehensive course on the legal framework protecting women in Bangladesh. Covers domestic violence protection, inheritance rights, workplace rights, and family law. Taught in Bengali to maximize accessibility.',
            'intermediate', 'Bengali',
            [
                [1, 'Legal Framework for Women\'s Rights in Bangladesh', 'Bangladesh has enacted multiple laws to protect women: the Women and Children Repression Prevention Act 2000, Domestic Violence Act 2010, Dowry Prohibition Act 1980, and relevant provisions in the family laws. Constitutional Article 28 guarantees gender equality. This module maps the entire legal landscape for women\'s rights.', 25, true],
                [2, 'Domestic Violence Laws and Protection Orders', 'The Domestic Violence (Prevention and Protection) Act 2010 defines domestic violence broadly to include physical, psychological, sexual, and economic abuse. A survivor can apply for a protection order at any Magistrate Court. The order can prohibit the abuser from entering the home, contacting the survivor, or disposing of joint assets. Enforcement violations carry up to 2 years imprisonment.', 30, true],
                [3, 'Inheritance and Property Rights for Women', 'Under Muslim Personal Law, a daughter inherits half the share of a son from her father\'s estate. Widows are entitled to a share of the husband\'s property. For Hindus, the Hindu Succession Act applies. This module explains inheritance shares, the succession certificate process, and how women can legally challenge unlawful dispossession of inherited property.', 30, false],
                [4, 'Workplace Rights and Sexual Harassment', 'Women workers are protected by the Labor Act 2006 provisions on maternity benefits, equal pay, and safe working conditions. The High Court issued landmark guidelines in 2009 (following Ershad Ali vs. Sabura Khatun) on preventing sexual harassment at the workplace. This module covers reporting mechanisms, employer obligations, and remedies available to survivors.', 30, false],
                [5, 'Women\'s Rights in Family Law', 'Personal status matters — marriage, divorce, maintenance, and child custody — are governed by Muslim Family Laws Ordinance 1961 for Muslims, the Special Marriage Act 1872, and applicable Hindu and Christian personal laws. This module focuses on registration of marriage, rights to maintenance (nafaqa), and the procedure for obtaining khula (divorce initiated by wife).', 35, false],
            ]
        );

        $p6 = $this->createProgram(
            $l3,
            $areas['environmental-law'],
            'Environmental Laws of Bangladesh',
            'An advanced English-language course on environmental regulation in Bangladesh. Covers the Environment Conservation Act 1995, pollution control standards, EIA requirements, and Bangladesh\'s international obligations under multilateral environmental agreements.',
            'advanced', 'English',
            [
                [1, 'Bangladesh Environment Conservation Act 1995', 'The Environment Conservation Act 1995 (amended 2010) is the cornerstone of environmental law in Bangladesh. It establishes the Department of Environment (DoE) and grants it sweeping powers to regulate, inspect, and penalize polluters. The Act classifies industries into four categories (Green, Orange A, Orange B, Red) based on their environmental impact.', 30, true],
                [2, 'Pollution Control and Industrial Compliance', 'Industries must obtain Environmental Clearance Certificates (ECC) from the DoE before commencing operations. Discharge standards govern effluents, emissions, and solid waste. This module covers the compliance calendar, permissible limits for key pollutants, and the inspection and enforcement process including Show Cause Notices and fines up to BDT 10 lakh.', 35, true],
                [3, 'Environmental Impact Assessment', 'An Environmental Impact Assessment (EIA) is mandatory for Red category industries and development projects. The EIA process includes screening, scoping, assessment, public consultation, and submission to DoE for approval. This module explains how to read and challenge an EIA report, and the legal consequences of proceeding without approval.', 35, false],
                [4, 'International Environmental Agreements and Bangladesh', 'Bangladesh is party to major multilateral environmental agreements including the Paris Agreement on climate change, the Basel Convention on hazardous waste, the Ramsar Convention on wetlands, and the Convention on Biological Diversity. This module explains how these international obligations are incorporated into domestic law and the enforcement gaps that still exist.', 30, false],
            ]
        );

        $p7 = $this->createProgram(
            $l1,
            $areas['labor-law'],
            'Workplace Safety and Workers\' Rights',
            'A beginner-friendly Bengali course on occupational health and safety in Bangladeshi workplaces. Essential for garment workers, construction workers, and small business employees. Learn to identify hazards, claim compensation, and report violations.',
            'beginner', 'Bengali',
            [
                [1, 'Workplace Safety Standards in Bangladesh', 'The Bangladesh Labor Act 2006 and the Bangladesh National Building Code set minimum safety standards for all workplaces. Employers must ensure proper ventilation, lighting, sanitation, emergency exits, and fire safety equipment. This module explains what a safe workplace looks like and what workers can demand from their employers.', 20, true],
                [2, 'Occupational Health Hazards', 'Workers in garment, tannery, and construction industries face specific occupational hazards including chemical exposure, heat stress, noise-induced hearing loss, and dust-related lung diseases. Employers must conduct regular health checkups and provide Personal Protective Equipment (PPE). This module identifies common hazards and workers\' rights to a healthy environment.', 25, false],
                [3, 'Workers\' Compensation for Injuries', 'When a worker is injured at work, they are entitled to compensation under the Employees Compensation Act. The amount depends on the severity of injury and the worker\'s monthly wage. Employers must report workplace accidents within 7 days. This module explains the compensation calculation formula, claim timeline, and what to do if the employer refuses to pay.', 30, false],
                [4, 'Reporting Violations and Whistleblower Protection', 'Workers can report safety violations to the Department of Inspection for Factories and Establishments (DIFE) anonymously. The Inspector can issue improvement notices and, in serious cases, close down a facility. This module explains how to make a complaint, what evidence to gather, and protections available to workers who blow the whistle on unsafe practices.', 25, false],
            ]
        );

        $p8 = $this->createProgram(
            $l4,
            $areas['constitutional-rights'],
            'Land and Property Ownership Rights',
            'An intermediate course covering land rights, property registration, and dispute resolution in Bangladesh. Essential for anyone who owns, inherits, or plans to purchase land. Covers the Registration Act, mutation process, and legal remedies for land disputes.',
            'intermediate', 'Bengali',
            [
                [1, 'Property Rights Under the Bangladesh Constitution', 'Article 42 of the Constitution guarantees the right to property for every citizen. The state can only acquire private property for public purposes and with lawful compensation. This module explains the constitutional foundation of land rights and how it interacts with specific property legislation in Bangladesh.', 25, true],
                [2, 'Land Registration and Mutation Process', 'All land transfers in Bangladesh must be registered under the Registration Act 1908 at the Sub-Registrar\'s office. After registration, the new owner must apply for mutation (namjari) at the Assistant Commissioner (Land) office to update government records. This module walks through both processes step-by-step with the required documents and fees.', 30, true],
                [3, 'Common Land Disputes and Resolution', 'The most common land disputes in Bangladesh involve forged documents, boundary disputes, encroachment, illegal occupation, and inheritance disagreements. Disputes are handled by Civil Courts (Munsif, District Judge, High Court) or the Land Tribunal. This module covers how to identify fraudulent documents and the fastest routes to resolution.', 30, false],
                [4, 'Adverse Possession and Ownership Claims', 'Under the Limitation Act 1908, a person who continuously and openly possesses someone else\'s land for 12 years may acquire legal title through adverse possession (benami). This module explains the conditions, legal procedure to establish title, and how to defend your land from adverse possession claims by encroachers.', 25, false],
                [5, 'Land Acquisition by the Government', 'The Acquisition and Requisition of Immovable Property Ordinance 1982 governs how the government acquires private land for public purposes. Landowners are entitled to market value compensation. This module covers the acquisition procedure, how to challenge an inadequate compensation award, and the role of the Land Acquisition Collector.', 30, false],
            ]
        );

        return [$p1, $p2, $p3, $p4, $p5, $p6, $p7, $p8];
    }

    private function createProgram(
        User $lawyer,
        int $areaId,
        string $title,
        string $description,
        string $level,
        string $language,
        array $modulesData
    ): Program {
        $program = Program::create([
            'lawyer_id'     => $lawyer->id,
            'legal_area_id' => $areaId,
            'title'         => $title,
            'slug'          => Str::slug($title),
            'description'   => $description,
            'level'         => $level === 'beginner' ? 'basic' : $level,
            'language'      => $language,
            'status'        => 'published',
        ]);

        foreach ($modulesData as [$order, $modTitle, $content, $duration, $isFree]) {
            Module::create([
                'program_id'       => $program->id,
                'title'            => $modTitle,
                'content'          => $content,
                'order_index'      => $order,
                'duration_minutes' => $duration,
                'is_free'          => $isFree,
            ]);
        }

        // Reload modules relationship so we can access them by order_index later
        $program->load('modules');

        return $program;
    }

    private function createAssessment(Module $module, string $title, array $questions): void
    {
        $assessment = Assessment::create([
            'module_id'          => $module->id,
            'title'              => $title,
            'passing_score'      => 60,
            'time_limit_minutes' => 15,
        ]);

        foreach ($questions as $qData) {
            $question = AssessmentQuestion::create([
                'assessment_id' => $assessment->id,
                'question'      => $qData['q'],
                'type'          => $qData['type'],
                'points'        => $qData['pts'],
            ]);

            foreach ($qData['opts'] as $optData) {
                AssessmentOption::create([
                    'question_id' => $question->id,
                    'option_text' => $optData['text'],
                    'is_correct'  => $optData['correct'],
                ]);
            }
        }
    }

    private function completeRegistration(User $citizen, Program $program, string $registeredAt, string $completedAt): void
    {
        $modules = $program->modules;
        $total   = $modules->count();

        $registration = Registration::create([
            'citizen_id'    => $citizen->id,
            'program_id'    => $program->id,
            'registered_at' => $registeredAt,
            'completed_at'  => $completedAt,
        ]);

        $completedDate = \Carbon\Carbon::parse($completedAt);
        foreach ($modules as $index => $module) {
            ModuleProgress::create([
                'registration_id'   => $registration->id,
                'module_id'         => $module->id,
                'completed_at'      => $completedDate->copy()->subDays($total - $index - 1),
                'time_spent_seconds' => rand(900, 2400),
            ]);
        }

        Certificate::create([
            'citizen_id'       => $citizen->id,
            'program_id'       => $program->id,
            'certificate_code' => 'CERT-' . strtoupper(substr(md5($citizen->id . '-' . $program->id), 0, 10)),
            'issued_at'        => $completedAt,
        ]);
    }

    private function inProgressRegistration(User $citizen, Program $program, string $registeredAt, int $modulesCompleted): void
    {
        $registration = Registration::create([
            'citizen_id'    => $citizen->id,
            'program_id'    => $program->id,
            'registered_at' => $registeredAt,
            'completed_at'  => null,
        ]);

        $modules = $program->modules->take($modulesCompleted);
        $baseDate = \Carbon\Carbon::parse($registeredAt);

        foreach ($modules as $index => $module) {
            ModuleProgress::create([
                'registration_id'    => $registration->id,
                'module_id'          => $module->id,
                'completed_at'       => $baseDate->copy()->addDays($index + 1),
                'time_spent_seconds' => rand(900, 2400),
            ]);
        }
    }

    private function bareRegistration(User $citizen, Program $program, string $registeredAt): void
    {
        Registration::create([
            'citizen_id'    => $citizen->id,
            'program_id'    => $program->id,
            'registered_at' => $registeredAt,
            'completed_at'  => null,
        ]);
    }
}
