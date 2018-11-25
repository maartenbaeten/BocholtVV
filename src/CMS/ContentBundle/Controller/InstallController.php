<?php

namespace CMS\ContentBundle\Controller;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InstallController extends Controller
{
    public function mainAction(Request $request, $param = null)
    {
        $path = realpath($_SERVER['DOCUMENT_ROOT'] . '/../app/config');
        $paramFile = '/parameters.yml';
        $configFile = '/config.yml';
        switch ($param) {
            case 2 : {
                $form = $this->createFormBuilder()
                    ->add('db_name', 'text')
                    ->add('host', 'text')
                    ->add('username', 'text')
                    ->add('password', 'password')
                    ->add('submit', 'submit', ['label' => 'submit', 'attr' => ['class' => 'btn btn-primary btn-lg outline']])
                    ->getForm();
                $form->handleRequest($request);
                    if ($form->isValid()) {
                        $tsk = $form->getData();
                        $patterns[] = '/database_host:(\s)+(\S+)/';
                        $replacements[] = 'database_host: ' . $tsk['host'];
                        $patterns[] = '/database_name:(\s)+(\S+)/';
                        $replacements[] = 'database_name: ' . $tsk['db_name'];
                        $patterns[] = '/database_user:(\s)+(\S+)/';
                        $replacements[] = 'database_user: ' . $tsk['username'];
                        $patterns[] = '/database_password:(\s)+(\S+)/';
                        $replacements[] = 'database_password: ' . $tsk['password'];
                        $config = file_get_contents($path . $paramFile);
                        $config = preg_replace($patterns, $replacements, $config);
                        file_put_contents($path . $paramFile, $config);

                        return $this->redirect($this->generateUrl('cms_install', ['param' => 3]));
                    };

                return $this->render('CMSContentBundle:Install:install.html.twig', ['form' => $form->createView(), 'param' => $param]);
            };
                break;
            case 3 : {
                $form = $this->createFormBuilder()
                    ->add('email', 'text', ['required' => false])
                    ->add('password', 'text', ['required' => false] )
                    ->add('mail_server', 'text', ['required' => false])
                    ->add('submit', 'submit', ['label' => 'submit', 'attr' => ['class' => 'btn btn-primary btn-lg outline']])
                    ->add('finish', 'submit', ['label' => 'skip', 'attr' => ['class' => 'btn btn-primary btn-lg outline']])
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isValid()) {
                    if ($form->get('submit')->isClicked()) {
                        $tsk = $form->getData();
                        $patterns[] = '/mailer_host:(\s)+(\S+)/';
                        $replacements[] = 'mailer_host: ' . $tsk['mail_server'];
                        $patterns[] = '/mailer_user:(\s)+(\S+)/';
                        $replacements[] = 'mailer_user: ' . $tsk['email'];
                        $patterns[] = '/mailer_password:(\s)+(\S+)/';
                        $replacements[] = 'mailer_password: ' . $tsk['password'];
                        $config = file_get_contents($path . $paramFile);
                        $config = preg_replace($patterns, $replacements, $config);
                        file_put_contents($path . $paramFile, $config);
                    }

                        return $this->redirect($this->generateUrl('cms_install', ['param' => 4]));

                };

                return $this->render('CMSContentBundle:Install:install.html.twig', ['form' => $form->createView(), 'param' => $param]);
            };
                break;
            case 4 : {
                $form = $this->createFormBuilder()
                    ->add('username', 'text')
                    ->add('password', 'password')
                    ->add('email', 'text')
                    ->add('submit', 'submit', ['label' => 'submit', 'attr' => ['class' => 'btn btn-primary btn-lg outline']])
                    ->add('finish', 'submit', ['label' => 'skip', 'attr' => ['class' => 'btn btn-primary btn-lg outline']])
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isValid()) {
                    if ($form->get('submit')->isClicked()) {
                        $tsk = $form->getData();
                        shell_exec('cd .. && app/console fos:user:create ' . $tsk['username'] . ' ' . $tsk['password'] . ' ' . $tsk['email'] . ' && app/console fos:user:promote ' . $tsk['username'] . ' --super');

                        return $this->redirect('/');
                    }
                    if ($form->get('finish')->isClicked()) {

                        return $this->redirect('/');
                    }
                };

                return $this->render('CMSContentBundle:Install:install.html.twig', ['form' => $form->createView(), 'param' => $param]);
            };
                break;

            default: {
                $form = $this->createFormBuilder()
                    ->add('language', 'choice', [
                        'choices' => [
                            'fr' => 'french',
                            'nl' => 'dutch',
                            'en' => 'english',
                        ]
                    ])
                    ->add('submit', 'submit', ['label' => 'submit', 'attr' => ['class' => 'btn btn-primary btn-lg outline']])
                    ->getForm();
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $tsk = $form->getData();
                    $config = file_get_contents($path . $configFile);
                    $config = preg_replace(['/fallback:(.)(\S+)/', '/default_locale:\s+"(\S+)"/'], ['fallback: ' . $tsk['language'], 'default_locale: "' . $tsk['language'] . '"'], $config);
                    file_put_contents($path . $configFile, $config);

                    $config = file_get_contents($path . $paramFile);
                    $config = preg_replace(['/locale:(\s+)(\S+)/'], ['locale: ' . $tsk['language'] ], $config);
                    file_put_contents($path . $paramFile, $config);

                    return $this->redirect($this->generateUrl('cms_install', ['param' => 2], ['method' => 'POST']));

                };
                return $this->render('CMSContentBundle:Install:install.html.twig', ['form' => $form->createView()]);
            };
                break;
        }
    }
}