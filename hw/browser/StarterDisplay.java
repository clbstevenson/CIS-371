import javax.swing.*;
import java.awt.*;
import java.util.HashMap;
import java.util.List;
import java.util.ArrayList;
import java.util.Map;
import java.util.Scanner;

/**
 * This class demonstrates a simple technique of laying out text "by hand"
 * <p/>
 * Also demonstrates how to change fonts and colors.
 * <p/>
 * Created by kurmasz on 12/17/14.
 */
public class StarterDisplay extends JPanel {

  private static final int MARGIN = 10; // the margin around the edge of the window.
  private List<String> content;  // the text that is to be displayed.
  private Color defaultColor; // the default text color


  // This Map is what makes links:  Each Rectangle is a link --- an area on the screen that can be clicked.
  // The rectangle is the Key.  The Value, in this case, is the color that should be used when the link is clicked.
  // When building a "real" browser, the links are also areas on the screen, but the corresponding value is the URL
  // that should be loaded when the link is clicked.
  private Map<Rectangle, Color> links = new HashMap<Rectangle, Color>();
  private Map<Rectangle, String> realLinks = new HashMap<Rectangle, String>();


  /**
   * Set the text that is to be displayed.
   *
   * @param text_in the text that is to be displayed
   */
  public void setText(List<String> text_in) {
    content = text_in;
    defaultColor = Color.black;
  }

  /**
   * Set the text color
   *
   * @param c the desired text color
   */
  public void setColor(Color c) {
    defaultColor = c;
  }

  /**
   * Actually "draws" the text on the window.
   *
   * @param g
   */
  @Override
  public void paintComponent(Graphics g) {
    super.paintComponent(g);
    // If no file has been loaded yet, then do nothing.
    if (content == null) {
      return;
    }

    // The FontMetrics object can compute the size of text in the window.
    // You must get a new FontMetrics object every time you change or modify the font (e.g., use bold or italics).
    FontMetrics metrics = g.getFontMetrics();
    int line_height = metrics.getHeight();
    int panel_width = getWidth() - MARGIN * 2;

    int x = MARGIN;
    int y = line_height;

    // save the original font in case we change it.
    Font originalFont = g.getFont();


    boolean startBold = false;
    boolean endBold = false;
    boolean startItal = false;
    boolean endItal = false;
    boolean startLink = false;
    boolean endLink = false;
    String linkURL = null;
    String linkText = "";
    // Iterate over each line.
    for (String line : content) {
      Scanner words = new Scanner(line);

      System.out.println("~~");

      // iterate over each word.
      while (words.hasNext()) {

        int style = Font.PLAIN;
        String nextWord = words.next();


        // A simple example of how to handle a *one-word* markup 
        // Remember, your assignment will use multi-word markup.
        /*if (nextWord.startsWith("*") && nextWord.endsWith("*") && nextWord.length() > 1) {
          // remove the markup.
          nextWord = nextWord.substring(1, nextWord.length() - 1);
          style = Font.BOLD;
        }
        */
        // Starts with '*', start adding words to a list that will be bold.
        // Search until find another word that ends with '*'.
        if (nextWord.startsWith("*")) {
            startBold = true;
            //List<String> boldWords = new ArrayList<String>(); 
            // add the current word to the list, without the starting '*'.
            //style = Font.BOLD;
            if(nextWord.endsWith("*")) {
                endBold = true;
                // remove the '*' markup
                nextWord = nextWord.substring(1, nextWord.length() -1 );                //wordList.add(nextWord.substring(1, nextWord.length() -1) + " "); 
            } else {
                nextWord = nextWord.substring(1);
                //wordList.add(nextWord.substring(1) + " "); 
            }
        } else if(nextWord.endsWith("*")) {
            // remove the '*' markup
            nextWord = nextWord.substring(0, nextWord.length() -1 );
            //isBold = false;
            endBold = true;
            //style = Font.BOLD;
        }


        // Starts with '_', start adding words to a list that will be bold.
        // Search until find another word that ends with '_'.
        if (nextWord.startsWith("_")) {
            startItal = true;
            if(nextWord.endsWith("_")) {
                endItal = true;
                // remove the '_' markup
                nextWord = nextWord.substring(1, nextWord.length() -1 );
            } else {
                nextWord = nextWord.substring(1);
            }
        } else if(nextWord.endsWith("_")) {
            // remove the '_' markup
            nextWord = nextWord.substring(0, nextWord.length() -1 );
            endItal = true;
        } 
        
        // Update the font to Bold, Italic, or Bold|Italic
        if((startBold || endBold) && (startItal || endItal)) {
            style = Font.BOLD|Font.ITALIC;
        } else {
            if((startBold || endBold)) {
                style = Font.BOLD;
                //if(startBold)
            }
            if((startItal || endItal)) {
                style = Font.ITALIC;
                //if(startItal)
            } 
        }

        // "Turn Off" the bold marker
        if(endBold) {
            endBold = false;
            startBold = false;
        }
        // "Turn Off" the italic marker
        if(endItal) {
            endItal = false;
            startItal = false;
        }

        // Starts with '[[', then it is a link.
        // Continue searching until ']]'
        if (nextWord.startsWith("[[")) {
            startLink = true; 
            if(nextWord.endsWith("]]")) {
                endLink = true;
                // If the same words starts and ends with '[['
                // this means the URL is the link text.
                //linkURL = new MyURL(nextWord.substring(2,nextWord.length()-2));
                linkURL = nextWord.substring(2, nextWord.length()-2);
                linkText = nextWord.substring(2, nextWord.length()-2);
                nextWord = linkURL;
                
            } else {
                // Otherwise, the next few words are the link text
                // in place of the URL.    
                //linkURL = new MyURL(nextWord.substring(2));
                linkURL = nextWord.substring(2);
                nextWord = linkURL;
                // linkText will be whatever comes next
            }
        } else if(nextWord.endsWith("]]")) {
            // The link text is ending: create the link where the 
            // URL is not the link text.
            endLink = true;
            linkText += nextWord.substring(0,nextWord.length()-2);
            nextWord = linkText;
        } else if(startLink) {
            linkText += nextWord;
        }

        String wordAndSpace = nextWord + " ";
        g.setFont(originalFont.deriveFont(style));
        metrics = g.getFontMetrics();

        int word_width = metrics.stringWidth(wordAndSpace);

        // When reach the end of the link, create a Rectangle wrapping
        // the linkText, and the URL is the link value.
        if(endLink) {
            endLink = false;
            startLink = false;
            String linkTextAndSpace = linkText + " ";
            word_width = metrics.stringWidth(linkTextAndSpace);
            System.out.printf("linkTextAndSpace: %s\turl:%s\n", 
                linkTextAndSpace, linkURL.toString());

            // If there isn't room for this word, go to the next line
            if (x + word_width > panel_width) {
              x = MARGIN;
              y += line_height;
            }

            Rectangle rect = new Rectangle(x, y - line_height, word_width, line_height);
            realLinks.put(rect, linkURL);
            // draw the word
            //g.setFont(originalFont.deriveFont(style));
            g.setColor(Color.orange.darker());
            g.drawString(linkTextAndSpace, x, y);
            
        } else if(!startLink){
            // Otherwise, not in the middle of a link so draw normally. 
            // If there isn't room for this word, go to the next line
            if (x + word_width > panel_width) {
              x = MARGIN;
              y += line_height;
            }

            // A simple example of how to handle links. A word of the form (#123456) will be
            // represented as a link that, when clicked on, will change the text color.
            Color color = getColor(nextWord);
            if (color != null) {
              g.setColor(color);
              Rectangle rect = new Rectangle(x, y - line_height, word_width, line_height);
              links.put(rect, color);
              // g.drawRect(rect.x, rect.y, rect.width, rect.height);
            } else {
              g.setColor(defaultColor);
            }
            // draw the word
            //g.setFont(originalFont.deriveFont(style));
            g.drawString(wordAndSpace, x, y);
        }



        x += word_width;
        //}

      } // end of the line

      // move to the next line
      x = MARGIN;
      y += line_height;
    } // end of all ines

    // make this JPanel bigger if necessary.
    // Calling re-validate causes the scroll bars to adjust, if necessary.
    if (y > getHeight()) {
      setPreferredSize(new Dimension(x, y + line_height + 2 * MARGIN));
      revalidate();
    }
  }

  /**
   * Determine if the {@code word} represents a color.
   *
   * @param word the next word to be displayed
   * @return the {@code Color} represented by {@code word}, or {@code null} if {@code word} does not represent a color
   */

  private static Color getColor(String word) {
    if (word.length() == 9 && word.startsWith("(#") && word.endsWith(")")) {
      return new Color(Integer.parseInt(word.substring(2, 8), 16));
    } else {
      return null;
    }
  }

  /**
   * Return the color value of the color link at {@code point}, or
   * return {@code null} if {@code point} doesn't point to a color link.
   *
   * @param point the {@code Point} that was clicked.
   * @return the color value of the color link at {@code point}, or
   * return {@code null} if {@code point} doesn't point to a color link.
   */
  // 
  public Color getColor(Point point) {
    for (Map.Entry<Rectangle, Color> entry : links.entrySet()) {
      if (entry.getKey().contains(point)) {
        return entry.getValue();
      }
    }
    return null;
  }

  public String getLink(Point point) {
      for (Map.Entry<Rectangle, String> entry : realLinks.entrySet()) {
          if(entry.getKey().contains(point)) {
              return entry.getValue();
          }
      }
      return null;
  }
}
