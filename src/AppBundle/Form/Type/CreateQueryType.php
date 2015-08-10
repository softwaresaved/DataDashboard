<?php
/**
*  Function to create a form to 
*/

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use SSI\DataBundle\Entity\SSIQueryMeta;

class CreateQueryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $meta = new SSIQueryMeta();
        $tables = $meta->get_table_meta();
        $builder
            ->add('Title')
            ->add('Description')
            ->add('Fields', 'choice',array(
              'choices' => $tables,
              'multiple' => true,
              'expanded' => true,
            ))
            ->add('Visual', 'choice', array(
              'choices' => self::allowed_tables(), 
              'expanded' => true,
            ))
            ->add('Data', 'text', array('required' => false))
            ->add('save', 'submit');
    }

    public function getName()
    {
        return 'task';
    }

    private function allowed_tables ()
    {
       return array('pie' => 'Pie Chart', 'hist' => 'Histogram');
    }
}

?>
