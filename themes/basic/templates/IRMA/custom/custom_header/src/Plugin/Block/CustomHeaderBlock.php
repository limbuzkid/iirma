<?php
  namespace Drupal\custom_header\Plugin\Block;
  
  use Drupal\custom_pages\Controller\CustomPagesController;
  use Drupal\Core\Block\BlockBase;
  use Drupal\Component\Annotation\Plugin;
  use Drupal\Core\Annotation\Translation;
  use Drupal\Core\Url;
  use Drupal\Core\Link;
  use Drupal\Core\Password\PhpassHashedPassword;
/**
 * Provides a 'Custom Header' Block
 *
 * @Block(
 *   id = "custom_header",
 *   admin_label = @Translation("Custom Header"),
 * )
 */

class CustomHeaderBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
		$user = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());
		$uid  = $user->get('uid')->value;
		$name   = $user->get('name')->value;
		$user_email  = $user->get('mail')->value;
		$count = 1;
		
		if(!$uid) {
			$hashed       = new PhpassHashedPassword(2);
			$salt         = hash_hmac('sha256', 'AAMRII', 'iirmaa');
			$randm        = new CustomPagesController;
			$random       = $hashed->hash($randm->generate_random_string());
			$token        = md5($salt.$random);
			$_SESSION['custLog'] = $token;
		}
		

    $login_block = '<div class="flip-container"><div class="flipper">';
		if($uid) {
			$query = \Drupal::database()->select('users_extra', 'ue');
			$query->addField('ue', 'name');
			$query->condition('ue.entity_id', $uid);
			$query->range(0, 1);
			$username = $query->execute()->fetchField();
			
			$login_block .= '<div class="front">
                                <!-- back content -->
                                <div class="loginDivSec postLogin">
                                    <div class="innerWrapper">
                                        <p>'.$username.'</p>
                                        <p><a href="/my-account/'.$uid.'">My account</a></p>
                                        <p><a href="/change-password">Change password</a></p>
                                        <p><a href="/user-logout">Log out</a></p>
                                    </div>
                                </div>
                            </div>';
		} else {
			$login_block .= '<div class="front">
                                <div class="loginDivSec">
                                    <div class="innerWrapper">
                                        <label>Email</label><input type="text" value="" class="emailLgn">
                                        <label>Password</label><input type="password" value="" class="passwordLgn">
                                        <div class="submitBtn loginBtn">
                                            <input type="submit" class="irmaLogin" value="Submit">
                                            <a id="forgotPwd" href="/forgot-password">Forgot Password</a>
                                        </div>
                                        <p>Don\'t have an account? <a href="/register">Sign up now!</a></p>
                                    </div>
                                    <input type="hidden" id="csrfToken" value="'.$random.'">
																		<input type="hidden" id="rndToken" value="'.$token.'">
                                </div>
                            </div>';
		}
    
    
    $login_block .= '</div></div>';
    $hor_mnu = '';
    $ver_mnu = '';
    $tree = \Drupal::menuTree()->load('main', new \Drupal\Core\Menu\MenuTreeParameters());
		$current_path = \Drupal::service('path.current')->getPath();
		$result = \Drupal::service('path.alias_manager')->getAliasByPath($current_path);
		$result_arr = explode('/', $result);
		//print_r($result_arr);
    foreach ($tree as $item) {
      if($item->link->isEnabled()) {
        $title = $item->link->getTitle();
        $url_obj = $item->link->getUrlObject();
        $url_string = $url_obj->toString();
				$uri_arr = explode('/', $url_string);

        if($title == 'Home') {
          $hor_mnu .= '<li><a class="homeIcon" href="/"></a></li>';
        } else {
          //if($title == 'Login') {
					if($title == 'Register') {
						if($uid) {
							$hor_mnu .= '<li><a href="javascript:;">My Account</a>'.$login_block.'</li>';
						} else {
							//$hor_mnu .= '<li class="'.$title.'"><a href="javascript:;">'.$title.'</a>'.$login_block.'</li>';
							$hor_mnu .= '<li class="Login"><a href="javascript:;">Login</a>'.$login_block.'</li>';
						}
            
          } else {
            $hor_mnu .= '<li class="'.$title.'"><a href="'.$url_string.'">'.$title.'</a></li>';
          }
          
        }
        
        if($item->hasChildren) {
					$str = $uri_arr[1].'/';
					if(preg_match('/'.$uri_arr[1].'\//', $result)) {
						$ver_mnu .= '<li class="sublist"><a href="javascript:;" class="active">'.$title.'</a><ul class="sublink">';
					} else {
						$ver_mnu .= '<li class="sublist"><a href="javascript:;">'.$title.'</a><ul class="sublink">';
					}
          foreach($item->subtree as $child) {
            if($child->link->isEnabled()) {
              $level_next_title = $child->link->getTitle();
              $level_next_url = $child->link->getUrlObject()->toString();
							$level_next_arr = explode('/', $level_next_url);
              if($child->hasChildren) {
								if(strtolower($level_next_title) == 'join the network' || strtolower($level_next_title) == 'careers' || strtolower($level_next_title) == 'events' || strtolower($level_next_title) == 'avail campus facilities' || strtolower($level_next_title) == 'get involved' || strtolower($level_next_title) == 'alumni news') {
									if(preg_match('/'.$level_next_arr[2].'/', $result)) {
										$ver_mnu .= '<li class="sublist nomargin"><a href="javascript:;" class="active">'.$level_next_title.'</a><ul>';
									} else {
										$ver_mnu .= '<li class="sublist nomargin"><a href="javascript:;">'.$level_next_title.'</a><ul>';
									}
								} else {
									if(preg_match('/'.$level_next_arr[2].'/', $result)) {
										$ver_mnu .= '<li class="sublist nomargin"><a href="'.$level_next_url.'" class="active">'.$level_next_title.'</a><ul>';
									} else {
										$ver_mnu .= '<li class="sublist nomargin"><a href="'.$level_next_url.'">'.$level_next_title.'</a><ul>';
									}
								}
								if(strtolower($level_next_title) != 'careers' && strtolower($level_next_title) != 'events' && strtolower($level_next_title) != 'alumni news') {
									if($level_next_arr[2] == 'join-the-network') {
										if(!isset($result_arr[3]) && $result_arr[2] == 'join-the-network') {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'" class="active">'.$level_next_title.'</a></li>';
										} else {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'">'.$level_next_title.'</a></li>';
										}
									} else if($level_next_arr[2] == 'avail-campus-facilities') {
										if(!isset($result_arr[3]) && $result_arr[2] == 'avail-campus-facilities') {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'" class="active">'.$level_next_title.'</a></li>';
										} else {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'">'.$level_next_title.'</a></li>';
										}
									} else if($level_next_arr[2] == 'get-involved') {
										if(!isset($result_arr[3]) && $result_arr[2] == 'get-involved') {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'" class="active">'.$level_next_title.'</a></li>';
										} else {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'">'.$level_next_title.'</a></li>';
										}
									} else {
										if(preg_match('/'.$level_next_arr[3].'/', $result)) {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'" class="active">'.$level_next_title.'</a></li>';
										} else {
											$ver_mnu .= '<li><a title="'.$level_next_title.'" href="'.$level_next_url.'">'.$level_next_title.'</a></li>';
										}
									}
								}
								
				  
                foreach($child->subtree as $gchild) {
                  if($gchild->link->isEnabled()) {
                    $gchild_title = $gchild->link->getTitle();
                    $gchild_link = $gchild->link->getUrlObject()->toString();
										$gchild_arr = explode('/', $gchild_link);
                    if($gchild_title == 'Alumni News Listing') {
											if(!isset($result_arr[3]) && $result_arr[2] == 'alumni-news') {
												$ver_mnu .= '<li><a title="'.$gchild_title.'" href="'.$gchild_link.'" class="active">'.$gchild_title.'</a></li>';
											} else {
												$ver_mnu .= '<li><a title="'.$gchild_title.'" href="'.$gchild_link.'">'.$gchild_title.'</a></li>';
											}
										} else {
											if(preg_match('/'.$gchild_arr[3].'/', $result)) {
												$ver_mnu .= '<li><a title="'.$gchild_title.'" href="'.$gchild_link.'" class="active">'.$gchild_title.'</a></li>';
											} else {
												$ver_mnu .= '<li><a title="'.$gchild_title.'" href="'.$gchild_link.'">'.$gchild_title.'</a></li>';
											}
										}
                  }
                }
                $ver_mnu .= '</ul></li>';
              } else {
								if($result == $level_next_url) {
									$ver_mnu .= '<li><a href="'.$level_next_url.'" class="active">'.$level_next_title.'</a></li>';
								} else {
									$ver_mnu .= '<li><a href="'.$level_next_url.'">'.$level_next_title.'</a></li>';
								}
              }
            }
          }
          $ver_mnu .= '</ul></li>';
        } else {
          if($title == 'Home') {
						if(!isset($result_arr[2]) && $result_arr[1] == 'node') {
							$ver_mnu .= '<li><a href="/" class="active">'.$title.'</a></li>';
						} else {
							$ver_mnu .= '<li><a href="/">'.$title.'</a></li>';
						}
          } else if($title == 'Register') {
						if($uid <= 0) {
							if($result_arr[1] == 'register') {
								$ver_mnu .= '<li><a href="'.$url_string.'" class="active">'.$title.'</a></li>';
							} else {
							$ver_mnu .= '<li><a href="'.$url_string.'">'.$title.'</a></li>';
							}
						}
						
					} else {
						if($path == '/'.$result_arr[1]) {
							$ver_mnu .= '<li><a href="'.$url_string.'" class="active">'.$title.'</a></li>';
						} else {
							$ver_mnu .= '<li><a href="'.$url_string.'">'.$title.'</a></li>';
						}
						
					}						
        }
      }  
    }
    
    $header_html = '<header id="header"><div id="menu" class="menu"><div class="nav-icon2">
                    <span></span><span></span><span></span><span></span><span></span><span></span></div> <label>MENU</label></div>
                    <div class="logo"><a href="/"><img src="/sites/default/files/logo_0.png" alt="IRMA Almnium"></a></div>
                    <nav class="mainNavigation"><ul>'.$hor_mnu.'</ul><div class="searchSec"><a href="javascript:;"></a></div></nav></header>';
    $header_html .= '<section class="navigation"><div class="mainWrapper"><div class="navigation-list"><ul>'.$ver_mnu.'</ul></div></div><div class="close-mobile">
                    <a href="javascript:;"><img src="/themes/basic/images/close.png" width="53" height="48" alt="close"></a></div></section>';
    
    return array(
      '#markup' => $this->t($header_html),
      /* If you want to bypass Drupal 8's default caching for this block then simply add this, otherwise remove the next three line */
      '#cache' => array(
          'max-age' => 0,
      ),
    );
  }
}
