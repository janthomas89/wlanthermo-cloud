<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MeasurementTimeSeries
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\MeasurementTimeSeriesRepository")
 */
class MeasurementTimeSeries
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var MeasurementProbe
     *
     * @ORM\ManyToOne(targetEntity="MeasurementProbe")
     * @ORM\JoinColumn(name="measuremenProbetId", referencedColumnName="id")
     */
    private $measurementProbe;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;

    /**
     * @var integer
     *
     * @ORM\Column(name="s0", type="smallint")
     */
    private $s0;

    /**
     * @var integer
     *
     * @ORM\Column(name="s1", type="smallint")
     */
    private $s1;

    /**
     * @var integer
     *
     * @ORM\Column(name="s2", type="smallint")
     */
    private $s2;

    /**
     * @var integer
     *
     * @ORM\Column(name="s3", type="smallint")
     */
    private $s3;

    /**
     * @var integer
     *
     * @ORM\Column(name="s4", type="smallint")
     */
    private $s4;

    /**
     * @var integer
     *
     * @ORM\Column(name="s5", type="smallint")
     */
    private $s5;

    /**
     * @var integer
     *
     * @ORM\Column(name="s6", type="smallint")
     */
    private $s6;

    /**
     * @var integer
     *
     * @ORM\Column(name="s7", type="smallint")
     */
    private $s7;

    /**
     * @var integer
     *
     * @ORM\Column(name="s8", type="smallint")
     */
    private $s8;

    /**
     * @var integer
     *
     * @ORM\Column(name="s9", type="smallint")
     */
    private $s9;

    /**
     * @var integer
     *
     * @ORM\Column(name="s10", type="smallint")
     */
    private $s10;

    /**
     * @var integer
     *
     * @ORM\Column(name="s11", type="smallint")
     */
    private $s11;

    /**
     * @var integer
     *
     * @ORM\Column(name="s12", type="smallint")
     */
    private $s12;

    /**
     * @var integer
     *
     * @ORM\Column(name="s13", type="smallint")
     */
    private $s13;

    /**
     * @var integer
     *
     * @ORM\Column(name="s14", type="smallint")
     */
    private $s14;

    /**
     * @var integer
     *
     * @ORM\Column(name="s15", type="smallint")
     */
    private $s15;

    /**
     * @var integer
     *
     * @ORM\Column(name="s16", type="smallint")
     */
    private $s16;

    /**
     * @var integer
     *
     * @ORM\Column(name="s17", type="smallint")
     */
    private $s17;

    /**
     * @var integer
     *
     * @ORM\Column(name="s18", type="smallint")
     */
    private $s18;

    /**
     * @var integer
     *
     * @ORM\Column(name="s19", type="smallint")
     */
    private $s19;

    /**
     * @var integer
     *
     * @ORM\Column(name="s20", type="smallint")
     */
    private $s20;

    /**
     * @var integer
     *
     * @ORM\Column(name="s21", type="smallint")
     */
    private $s21;

    /**
     * @var integer
     *
     * @ORM\Column(name="s22", type="smallint")
     */
    private $s22;

    /**
     * @var integer
     *
     * @ORM\Column(name="s23", type="smallint")
     */
    private $s23;

    /**
     * @var integer
     *
     * @ORM\Column(name="s24", type="smallint")
     */
    private $s24;

    /**
     * @var integer
     *
     * @ORM\Column(name="s25", type="smallint")
     */
    private $s25;

    /**
     * @var integer
     *
     * @ORM\Column(name="s26", type="smallint")
     */
    private $s26;

    /**
     * @var integer
     *
     * @ORM\Column(name="s27", type="smallint")
     */
    private $s27;

    /**
     * @var integer
     *
     * @ORM\Column(name="s28", type="smallint")
     */
    private $s28;

    /**
     * @var integer
     *
     * @ORM\Column(name="s29", type="smallint")
     */
    private $s29;

    /**
     * @var integer
     *
     * @ORM\Column(name="s30", type="smallint")
     */
    private $s30;

    /**
     * @var integer
     *
     * @ORM\Column(name="s31", type="smallint")
     */
    private $s31;

    /**
     * @var integer
     *
     * @ORM\Column(name="s32", type="smallint")
     */
    private $s32;

    /**
     * @var integer
     *
     * @ORM\Column(name="s33", type="smallint")
     */
    private $s33;

    /**
     * @var integer
     *
     * @ORM\Column(name="s34", type="smallint")
     */
    private $s34;

    /**
     * @var integer
     *
     * @ORM\Column(name="s35", type="smallint")
     */
    private $s35;

    /**
     * @var integer
     *
     * @ORM\Column(name="s36", type="smallint")
     */
    private $s36;

    /**
     * @var integer
     *
     * @ORM\Column(name="s37", type="smallint")
     */
    private $s37;

    /**
     * @var integer
     *
     * @ORM\Column(name="s38", type="smallint")
     */
    private $s38;

    /**
     * @var integer
     *
     * @ORM\Column(name="s39", type="smallint")
     */
    private $s39;

    /**
     * @var integer
     *
     * @ORM\Column(name="s40", type="smallint")
     */
    private $s40;

    /**
     * @var integer
     *
     * @ORM\Column(name="s41", type="smallint")
     */
    private $s41;

    /**
     * @var integer
     *
     * @ORM\Column(name="s42", type="smallint")
     */
    private $s42;

    /**
     * @var integer
     *
     * @ORM\Column(name="s43", type="smallint")
     */
    private $s43;

    /**
     * @var integer
     *
     * @ORM\Column(name="s44", type="smallint")
     */
    private $s44;

    /**
     * @var integer
     *
     * @ORM\Column(name="s45", type="smallint")
     */
    private $s45;

    /**
     * @var integer
     *
     * @ORM\Column(name="s46", type="smallint")
     */
    private $s46;

    /**
     * @var integer
     *
     * @ORM\Column(name="s47", type="smallint")
     */
    private $s47;

    /**
     * @var integer
     *
     * @ORM\Column(name="s48", type="smallint")
     */
    private $s48;

    /**
     * @var integer
     *
     * @ORM\Column(name="s49", type="smallint")
     */
    private $s49;

    /**
     * @var integer
     *
     * @ORM\Column(name="s50", type="smallint")
     */
    private $s50;

    /**
     * @var integer
     *
     * @ORM\Column(name="s51", type="smallint")
     */
    private $s51;

    /**
     * @var integer
     *
     * @ORM\Column(name="s52", type="smallint")
     */
    private $s52;

    /**
     * @var integer
     *
     * @ORM\Column(name="s53", type="smallint")
     */
    private $s53;

    /**
     * @var integer
     *
     * @ORM\Column(name="s54", type="smallint")
     */
    private $s54;

    /**
     * @var integer
     *
     * @ORM\Column(name="s55", type="smallint")
     */
    private $s55;

    /**
     * @var integer
     *
     * @ORM\Column(name="s56", type="smallint")
     */
    private $s56;

    /**
     * @var integer
     *
     * @ORM\Column(name="s57", type="smallint")
     */
    private $s57;

    /**
     * @var integer
     *
     * @ORM\Column(name="s58", type="smallint")
     */
    private $s58;

    /**
     * @var integer
     *
     * @ORM\Column(name="s59", type="smallint")
     */
    private $s59;

    /**
     * @var integer
     *
     * @ORM\Column(name="min", type="smallint")
     */
    private $min;

    /**
     * @var integer
     *
     * @ORM\Column(name="max", type="smallint")
     */
    private $max;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg", type="smallint")
     */
    private $avg;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg0", type="smallint")
     */
    private $avg0;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg1", type="smallint")
     */
    private $avg1;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg2", type="smallint")
     */
    private $avg2;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg3", type="smallint")
     */
    private $avg3;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg4", type="smallint")
     */
    private $avg4;

    /**
     * @var integer
     *
     * @ORM\Column(name="avg5", type="smallint")
     */
    private $avg5;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set measurementProbe
     *
     * @param MeasurementProbe $measurementProbe
     * @return MeasurementTimeSeries
     */
    public function setMeasurementProbe(MeasurementProbe $measurementProbe)
    {
        $this->measurementProbe = $measurementProbe;

        return $this;
    }

    /**
     * Get measurementProbe
     *
     * @return MeasurementProbe
     */
    public function getMeasurementProbe()
    {
        return $this->measurementProbe;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return MeasurementTimeSeries
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set s0
     *
     * @param integer $s0
     * @return MeasurementTimeSeries
     */
    public function setS0($s0)
    {
        $this->s0 = $s0;

        return $this;
    }

    /**
     * Get s0
     *
     * @return integer 
     */
    public function getS0()
    {
        return $this->s0;
    }



    /**
     * Set s1
     *
     * @param integer $s1
     * @return MeasurementTimeSeries
     */
    public function setS1($s1)
    {
        $this->s1 = $s1;

        return $this;
    }

    /**
     * Get s1
     *
     * @return integer
     */
    public function getS1()
    {
        return $this->s1;
    }

    /**
     * Set s2
     *
     * @param integer $s2
     * @return MeasurementTimeSeries
     */
    public function setS2($s2)
    {
        $this->s2 = $s2;

        return $this;
    }

    /**
     * Get s2
     *
     * @return integer
     */
    public function getS2()
    {
        return $this->s2;
    }

    /**
     * Set s3
     *
     * @param integer $s3
     * @return MeasurementTimeSeries
     */
    public function setS3($s3)
    {
        $this->s3 = $s3;

        return $this;
    }

    /**
     * Get s3
     *
     * @return integer
     */
    public function getS3()
    {
        return $this->s3;
    }

    /**
     * Set s4
     *
     * @param integer $s4
     * @return MeasurementTimeSeries
     */
    public function setS4($s4)
    {
        $this->s4 = $s4;

        return $this;
    }

    /**
     * Get s4
     *
     * @return integer
     */
    public function getS4()
    {
        return $this->s4;
    }

    /**
     * Set s5
     *
     * @param integer $s5
     * @return MeasurementTimeSeries
     */
    public function setS5($s5)
    {
        $this->s5 = $s5;

        return $this;
    }

    /**
     * Get s5
     *
     * @return integer
     */
    public function getS5()
    {
        return $this->s5;
    }

    /**
     * Set s6
     *
     * @param integer $s6
     * @return MeasurementTimeSeries
     */
    public function setS6($s6)
    {
        $this->s6 = $s6;

        return $this;
    }

    /**
     * Get s6
     *
     * @return integer
     */
    public function getS6()
    {
        return $this->s6;
    }

    /**
     * Set s7
     *
     * @param integer $s7
     * @return MeasurementTimeSeries
     */
    public function setS7($s7)
    {
        $this->s7 = $s7;

        return $this;
    }

    /**
     * Get s7
     *
     * @return integer
     */
    public function getS7()
    {
        return $this->s7;
    }

    /**
     * Set s8
     *
     * @param integer $s8
     * @return MeasurementTimeSeries
     */
    public function setS8($s8)
    {
        $this->s8 = $s8;

        return $this;
    }

    /**
     * Get s8
     *
     * @return integer
     */
    public function getS8()
    {
        return $this->s8;
    }

    /**
     * Set s9
     *
     * @param integer $s9
     * @return MeasurementTimeSeries
     */
    public function setS9($s9)
    {
        $this->s9 = $s9;

        return $this;
    }

    /**
     * Get s9
     *
     * @return integer
     */
    public function getS9()
    {
        return $this->s9;
    }

    /**
     * Set s10
     *
     * @param integer $s10
     * @return MeasurementTimeSeries
     */
    public function setS10($s10)
    {
        $this->s10 = $s10;

        return $this;
    }

    /**
     * Get s10
     *
     * @return integer
     */
    public function getS10()
    {
        return $this->s10;
    }

    /**
     * Set s11
     *
     * @param integer $s11
     * @return MeasurementTimeSeries
     */
    public function setS11($s11)
    {
        $this->s11 = $s11;

        return $this;
    }

    /**
     * Get s11
     *
     * @return integer
     */
    public function getS11()
    {
        return $this->s11;
    }

    /**
     * Set s12
     *
     * @param integer $s12
     * @return MeasurementTimeSeries
     */
    public function setS12($s12)
    {
        $this->s12 = $s12;

        return $this;
    }

    /**
     * Get s12
     *
     * @return integer
     */
    public function getS12()
    {
        return $this->s12;
    }

    /**
     * Set s13
     *
     * @param integer $s13
     * @return MeasurementTimeSeries
     */
    public function setS13($s13)
    {
        $this->s13 = $s13;

        return $this;
    }

    /**
     * Get s13
     *
     * @return integer
     */
    public function getS13()
    {
        return $this->s13;
    }

    /**
     * Set s14
     *
     * @param integer $s14
     * @return MeasurementTimeSeries
     */
    public function setS14($s14)
    {
        $this->s14 = $s14;

        return $this;
    }

    /**
     * Get s14
     *
     * @return integer
     */
    public function getS14()
    {
        return $this->s14;
    }

    /**
     * Set s15
     *
     * @param integer $s15
     * @return MeasurementTimeSeries
     */
    public function setS15($s15)
    {
        $this->s15 = $s15;

        return $this;
    }

    /**
     * Get s15
     *
     * @return integer
     */
    public function getS15()
    {
        return $this->s15;
    }

    /**
     * Set s16
     *
     * @param integer $s16
     * @return MeasurementTimeSeries
     */
    public function setS16($s16)
    {
        $this->s16 = $s16;

        return $this;
    }

    /**
     * Get s16
     *
     * @return integer
     */
    public function getS16()
    {
        return $this->s16;
    }

    /**
     * Set s17
     *
     * @param integer $s17
     * @return MeasurementTimeSeries
     */
    public function setS17($s17)
    {
        $this->s17 = $s17;

        return $this;
    }

    /**
     * Get s17
     *
     * @return integer
     */
    public function getS17()
    {
        return $this->s17;
    }

    /**
     * Set s18
     *
     * @param integer $s18
     * @return MeasurementTimeSeries
     */
    public function setS18($s18)
    {
        $this->s18 = $s18;

        return $this;
    }

    /**
     * Get s18
     *
     * @return integer
     */
    public function getS18()
    {
        return $this->s18;
    }

    /**
     * Set s19
     *
     * @param integer $s19
     * @return MeasurementTimeSeries
     */
    public function setS19($s19)
    {
        $this->s19 = $s19;

        return $this;
    }

    /**
     * Get s19
     *
     * @return integer
     */
    public function getS19()
    {
        return $this->s19;
    }

    /**
     * Set s20
     *
     * @param integer $s20
     * @return MeasurementTimeSeries
     */
    public function setS20($s20)
    {
        $this->s20 = $s20;

        return $this;
    }

    /**
     * Get s20
     *
     * @return integer
     */
    public function getS20()
    {
        return $this->s20;
    }

    /**
     * Set s21
     *
     * @param integer $s21
     * @return MeasurementTimeSeries
     */
    public function setS21($s21)
    {
        $this->s21 = $s21;

        return $this;
    }

    /**
     * Get s21
     *
     * @return integer
     */
    public function getS21()
    {
        return $this->s21;
    }

    /**
     * Set s22
     *
     * @param integer $s22
     * @return MeasurementTimeSeries
     */
    public function setS22($s22)
    {
        $this->s22 = $s22;

        return $this;
    }

    /**
     * Get s22
     *
     * @return integer
     */
    public function getS22()
    {
        return $this->s22;
    }

    /**
     * Set s23
     *
     * @param integer $s23
     * @return MeasurementTimeSeries
     */
    public function setS23($s23)
    {
        $this->s23 = $s23;

        return $this;
    }

    /**
     * Get s23
     *
     * @return integer
     */
    public function getS23()
    {
        return $this->s23;
    }

    /**
     * Set s24
     *
     * @param integer $s24
     * @return MeasurementTimeSeries
     */
    public function setS24($s24)
    {
        $this->s24 = $s24;

        return $this;
    }

    /**
     * Get s24
     *
     * @return integer
     */
    public function getS24()
    {
        return $this->s24;
    }

    /**
     * Set s25
     *
     * @param integer $s25
     * @return MeasurementTimeSeries
     */
    public function setS25($s25)
    {
        $this->s25 = $s25;

        return $this;
    }

    /**
     * Get s25
     *
     * @return integer
     */
    public function getS25()
    {
        return $this->s25;
    }

    /**
     * Set s26
     *
     * @param integer $s26
     * @return MeasurementTimeSeries
     */
    public function setS26($s26)
    {
        $this->s26 = $s26;

        return $this;
    }

    /**
     * Get s26
     *
     * @return integer
     */
    public function getS26()
    {
        return $this->s26;
    }

    /**
     * Set s27
     *
     * @param integer $s27
     * @return MeasurementTimeSeries
     */
    public function setS27($s27)
    {
        $this->s27 = $s27;

        return $this;
    }

    /**
     * Get s27
     *
     * @return integer
     */
    public function getS27()
    {
        return $this->s27;
    }

    /**
     * Set s28
     *
     * @param integer $s28
     * @return MeasurementTimeSeries
     */
    public function setS28($s28)
    {
        $this->s28 = $s28;

        return $this;
    }

    /**
     * Get s28
     *
     * @return integer
     */
    public function getS28()
    {
        return $this->s28;
    }

    /**
     * Set s29
     *
     * @param integer $s29
     * @return MeasurementTimeSeries
     */
    public function setS29($s29)
    {
        $this->s29 = $s29;

        return $this;
    }

    /**
     * Get s29
     *
     * @return integer
     */
    public function getS29()
    {
        return $this->s29;
    }

    /**
     * Set s30
     *
     * @param integer $s30
     * @return MeasurementTimeSeries
     */
    public function setS30($s30)
    {
        $this->s30 = $s30;

        return $this;
    }

    /**
     * Get s30
     *
     * @return integer
     */
    public function getS30()
    {
        return $this->s30;
    }

    /**
     * Set s31
     *
     * @param integer $s31
     * @return MeasurementTimeSeries
     */
    public function setS31($s31)
    {
        $this->s31 = $s31;

        return $this;
    }

    /**
     * Get s31
     *
     * @return integer
     */
    public function getS31()
    {
        return $this->s31;
    }

    /**
     * Set s32
     *
     * @param integer $s32
     * @return MeasurementTimeSeries
     */
    public function setS32($s32)
    {
        $this->s32 = $s32;

        return $this;
    }

    /**
     * Get s32
     *
     * @return integer
     */
    public function getS32()
    {
        return $this->s32;
    }

    /**
     * Set s33
     *
     * @param integer $s33
     * @return MeasurementTimeSeries
     */
    public function setS33($s33)
    {
        $this->s33 = $s33;

        return $this;
    }

    /**
     * Get s33
     *
     * @return integer
     */
    public function getS33()
    {
        return $this->s33;
    }

    /**
     * Set s34
     *
     * @param integer $s34
     * @return MeasurementTimeSeries
     */
    public function setS34($s34)
    {
        $this->s34 = $s34;

        return $this;
    }

    /**
     * Get s34
     *
     * @return integer
     */
    public function getS34()
    {
        return $this->s34;
    }

    /**
     * Set s35
     *
     * @param integer $s35
     * @return MeasurementTimeSeries
     */
    public function setS35($s35)
    {
        $this->s35 = $s35;

        return $this;
    }

    /**
     * Get s35
     *
     * @return integer
     */
    public function getS35()
    {
        return $this->s35;
    }

    /**
     * Set s36
     *
     * @param integer $s36
     * @return MeasurementTimeSeries
     */
    public function setS36($s36)
    {
        $this->s36 = $s36;

        return $this;
    }

    /**
     * Get s36
     *
     * @return integer
     */
    public function getS36()
    {
        return $this->s36;
    }

    /**
     * Set s37
     *
     * @param integer $s37
     * @return MeasurementTimeSeries
     */
    public function setS37($s37)
    {
        $this->s37 = $s37;

        return $this;
    }

    /**
     * Get s37
     *
     * @return integer
     */
    public function getS37()
    {
        return $this->s37;
    }

    /**
     * Set s38
     *
     * @param integer $s38
     * @return MeasurementTimeSeries
     */
    public function setS38($s38)
    {
        $this->s38 = $s38;

        return $this;
    }

    /**
     * Get s38
     *
     * @return integer
     */
    public function getS38()
    {
        return $this->s38;
    }

    /**
     * Set s39
     *
     * @param integer $s39
     * @return MeasurementTimeSeries
     */
    public function setS39($s39)
    {
        $this->s39 = $s39;

        return $this;
    }

    /**
     * Get s39
     *
     * @return integer
     */
    public function getS39()
    {
        return $this->s39;
    }

    /**
     * Set s40
     *
     * @param integer $s40
     * @return MeasurementTimeSeries
     */
    public function setS40($s40)
    {
        $this->s40 = $s40;

        return $this;
    }

    /**
     * Get s40
     *
     * @return integer
     */
    public function getS40()
    {
        return $this->s40;
    }

    /**
     * Set s41
     *
     * @param integer $s41
     * @return MeasurementTimeSeries
     */
    public function setS41($s41)
    {
        $this->s41 = $s41;

        return $this;
    }

    /**
     * Get s41
     *
     * @return integer
     */
    public function getS41()
    {
        return $this->s41;
    }

    /**
     * Set s42
     *
     * @param integer $s42
     * @return MeasurementTimeSeries
     */
    public function setS42($s42)
    {
        $this->s42 = $s42;

        return $this;
    }

    /**
     * Get s42
     *
     * @return integer
     */
    public function getS42()
    {
        return $this->s42;
    }

    /**
     * Set s43
     *
     * @param integer $s43
     * @return MeasurementTimeSeries
     */
    public function setS43($s43)
    {
        $this->s43 = $s43;

        return $this;
    }

    /**
     * Get s43
     *
     * @return integer
     */
    public function getS43()
    {
        return $this->s43;
    }

    /**
     * Set s44
     *
     * @param integer $s44
     * @return MeasurementTimeSeries
     */
    public function setS44($s44)
    {
        $this->s44 = $s44;

        return $this;
    }

    /**
     * Get s44
     *
     * @return integer
     */
    public function getS44()
    {
        return $this->s44;
    }

    /**
     * Set s45
     *
     * @param integer $s45
     * @return MeasurementTimeSeries
     */
    public function setS45($s45)
    {
        $this->s45 = $s45;

        return $this;
    }

    /**
     * Get s45
     *
     * @return integer
     */
    public function getS45()
    {
        return $this->s45;
    }

    /**
     * Set s46
     *
     * @param integer $s46
     * @return MeasurementTimeSeries
     */
    public function setS46($s46)
    {
        $this->s46 = $s46;

        return $this;
    }

    /**
     * Get s46
     *
     * @return integer
     */
    public function getS46()
    {
        return $this->s46;
    }

    /**
     * Set s47
     *
     * @param integer $s47
     * @return MeasurementTimeSeries
     */
    public function setS47($s47)
    {
        $this->s47 = $s47;

        return $this;
    }

    /**
     * Get s47
     *
     * @return integer
     */
    public function getS47()
    {
        return $this->s47;
    }

    /**
     * Set s48
     *
     * @param integer $s48
     * @return MeasurementTimeSeries
     */
    public function setS48($s48)
    {
        $this->s48 = $s48;

        return $this;
    }

    /**
     * Get s48
     *
     * @return integer
     */
    public function getS48()
    {
        return $this->s48;
    }

    /**
     * Set s49
     *
     * @param integer $s49
     * @return MeasurementTimeSeries
     */
    public function setS49($s49)
    {
        $this->s49 = $s49;

        return $this;
    }

    /**
     * Get s49
     *
     * @return integer
     */
    public function getS49()
    {
        return $this->s49;
    }

    /**
     * Set s50
     *
     * @param integer $s50
     * @return MeasurementTimeSeries
     */
    public function setS50($s50)
    {
        $this->s50 = $s50;

        return $this;
    }

    /**
     * Get s50
     *
     * @return integer
     */
    public function getS50()
    {
        return $this->s50;
    }

    /**
     * Set s51
     *
     * @param integer $s51
     * @return MeasurementTimeSeries
     */
    public function setS51($s51)
    {
        $this->s51 = $s51;

        return $this;
    }

    /**
     * Get s51
     *
     * @return integer
     */
    public function getS51()
    {
        return $this->s51;
    }

    /**
     * Set s52
     *
     * @param integer $s52
     * @return MeasurementTimeSeries
     */
    public function setS52($s52)
    {
        $this->s52 = $s52;

        return $this;
    }

    /**
     * Get s52
     *
     * @return integer
     */
    public function getS52()
    {
        return $this->s52;
    }

    /**
     * Set s53
     *
     * @param integer $s53
     * @return MeasurementTimeSeries
     */
    public function setS53($s53)
    {
        $this->s53 = $s53;

        return $this;
    }

    /**
     * Get s53
     *
     * @return integer
     */
    public function getS53()
    {
        return $this->s53;
    }

    /**
     * Set s54
     *
     * @param integer $s54
     * @return MeasurementTimeSeries
     */
    public function setS54($s54)
    {
        $this->s54 = $s54;

        return $this;
    }

    /**
     * Get s54
     *
     * @return integer
     */
    public function getS54()
    {
        return $this->s54;
    }

    /**
     * Set s55
     *
     * @param integer $s55
     * @return MeasurementTimeSeries
     */
    public function setS55($s55)
    {
        $this->s55 = $s55;

        return $this;
    }

    /**
     * Get s55
     *
     * @return integer
     */
    public function getS55()
    {
        return $this->s55;
    }

    /**
     * Set s56
     *
     * @param integer $s56
     * @return MeasurementTimeSeries
     */
    public function setS56($s56)
    {
        $this->s56 = $s56;

        return $this;
    }

    /**
     * Get s56
     *
     * @return integer
     */
    public function getS56()
    {
        return $this->s56;
    }

    /**
     * Set s57
     *
     * @param integer $s57
     * @return MeasurementTimeSeries
     */
    public function setS57($s57)
    {
        $this->s57 = $s57;

        return $this;
    }

    /**
     * Get s57
     *
     * @return integer
     */
    public function getS57()
    {
        return $this->s57;
    }

    /**
     * Set s58
     *
     * @param integer $s58
     * @return MeasurementTimeSeries
     */
    public function setS58($s58)
    {
        $this->s58 = $s58;

        return $this;
    }

    /**
     * Get s58
     *
     * @return integer
     */
    public function getS58()
    {
        return $this->s58;
    }

    /**
     * Set s59
     *
     * @param integer $s59
     * @return MeasurementTimeSeries
     */
    public function setS59($s59)
    {
        $this->s59 = $s59;

        return $this;
    }

    /**
     * Get s59
     *
     * @return integer
     */
    public function getS59()
    {
        return $this->s59;
    }

    /**
     * Set min
     *
     * @param integer $min
     * @return MeasurementTimeSeries
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return integer 
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param integer $max
     * @return MeasurementTimeSeries
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return integer 
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set avg
     *
     * @param integer $avg
     * @return MeasurementTimeSeries
     */
    public function setAvg($avg)
    {
        $this->avg = $avg;

        return $this;
    }

    /**
     * Get avg
     *
     * @return integer 
     */
    public function getAvg()
    {
        return $this->avg;
    }

    /**
     * Set avg0
     *
     * @param integer $avg0
     * @return MeasurementTimeSeries
     */
    public function setAvg0($avg0)
    {
        $this->avg0 = $avg0;

        return $this;
    }

    /**
     * Get avg0
     *
     * @return integer 
     */
    public function getAvg0()
    {
        return $this->avg0;
    }

    /**
     * Set avg1
     *
     * @param integer $avg1
     * @return MeasurementTimeSeries
     */
    public function setAvg1($avg1)
    {
        $this->avg1 = $avg1;

        return $this;
    }

    /**
     * Get avg1
     *
     * @return integer
     */
    public function getAvg1()
    {
        return $this->avg1;
    }

    /**
     * Set avg2
     *
     * @param integer $avg2
     * @return MeasurementTimeSeries
     */
    public function setAvg2($avg2)
    {
        $this->avg2 = $avg2;

        return $this;
    }

    /**
     * Get avg2
     *
     * @return integer
     */
    public function getAvg2()
    {
        return $this->avg2;
    }

    /**
     * Set avg3
     *
     * @param integer $avg3
     * @return MeasurementTimeSeries
     */
    public function setAvg3($avg3)
    {
        $this->avg3 = $avg3;

        return $this;
    }

    /**
     * Get avg3
     *
     * @return integer
     */
    public function getAvg3()
    {
        return $this->avg3;
    }

    /**
     * Set avg4
     *
     * @param integer $avg4
     * @return MeasurementTimeSeries
     */
    public function setAvg4($avg4)
    {
        $this->avg4 = $avg4;

        return $this;
    }

    /**
     * Get avg4
     *
     * @return integer
     */
    public function getAvg4()
    {
        return $this->avg4;
    }

    /**
     * Set avg5
     *
     * @param integer $avg5
     * @return MeasurementTimeSeries
     */
    public function setAvg5($avg5)
    {
        $this->avg5 = $avg5;

        return $this;
    }

    /**
     * Get avg5
     *
     * @return integer
     */
    public function getAvg5()
    {
        return $this->avg5;
    }
}
