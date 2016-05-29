import javax.swing.*;
import java.util.ArrayList;
import java.awt.*;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseAdapter;
import java.awt.event.MouseEvent;
import java.io.File;
import java.io.IOException;
import java.nio.charset.Charset;
import java.nio.file.Files;
import java.util.List;

/**
 * This class can serve as starter code for a simple web browser.
 * It provides a basic GUI setup:  and address bar, and a scrollable panel on which to draw.
 * It loads text from a local file and uses {@link StarterDisplay} to render it. 
 * <p/>
 * Created by kurmasz on 12/17/14.
 */
public class SimpleBrowser {


  private JFrame frame;
  protected JTextField addressBar;
  private JScrollPane scrollPane;
  private StarterDisplay display;
  private String homeLoc;

  // Caching images prevents the browser from repeatedly fetching the same image from the server
  // (This repeated fetching is especially annoying when scrolling.)
  protected ImageCache cache = new ImageCache();

  // The URL of the currently displayed document;
  protected MyURL currentURL = null;

  protected SimpleBrowser(String frameName, String initialLocation, JPanel displayPanel) {
    homeLoc = initialLocation;
    
    frame = new JFrame(frameName);
    frame.setSize(500, 500);
    addressBar = new JTextField(initialLocation);

    JPanel barPanel = new JPanel();
    barPanel.setLayout(new BorderLayout());
    JButton home = new JButton("Home");
    barPanel.add(home, BorderLayout.WEST);
    barPanel.add(addressBar, BorderLayout.CENTER);
    JButton basic = new JButton("Basic.txt");
    barPanel.add(basic, BorderLayout.EAST);
    
    Dimension screenSize = java.awt.Toolkit.getDefaultToolkit().getScreenSize();
    screenSize.width /= 2;
    screenSize.height /= 2;

    displayPanel.setPreferredSize(screenSize);
    scrollPane = new JScrollPane(displayPanel);


    frame.getContentPane().add(barPanel, BorderLayout.NORTH);
    frame.getContentPane().add(scrollPane, BorderLayout.CENTER);
    frame.setVisible(true);
    frame.setDefaultCloseOperation(JFrame.EXIT_ON_CLOSE);
    frame.pack();

    // Respond to the user pressing <enter> in the address bar.
    addressBar.addActionListener(new ActionListener() {
      @Override
      public void actionPerformed(ActionEvent e) {
        String textInBar = addressBar.getText();

        // Replace this with the code that loads
        // text from a web server
        loadPage(textInBar);
      }
    });
    
    home.addActionListener(new ActionListener() {
      @Override
      public void actionPerformed(ActionEvent e) {
        loadPage(homeLoc);
      }
    });

    basic.addActionListener(new ActionListener() {
        @Override
        public void actionPerformed(ActionEvent e) {
            loadPage("http://cis.gvsu.edu/~stevecal/sampleInput/basic.txt");
        }
    });
    

    displayPanel.addMouseListener(new MouseAdapter() {
      @Override
      public void mouseClicked(MouseEvent e) {
        super.mouseClicked(e);
        clicked(e.getPoint());
      }
    });

    currentURL = new MyURL("http://www.cis.gvsu.edu/~stevecal/sampleInput");
  }

  public SimpleBrowser(String frameName, String initialLocation, StarterDisplay display_in) {
    this(frameName, initialLocation, (JPanel) display_in);
    display = display_in;
  }

  protected void clicked(Point point) {
    // Respond to a mouse click in the display
    // TODO:  Override/replace this method when you add support for links.
    Color c = display.getColor(point);
    if (c != null) {
      display.setColor(c);
      display.repaint();
    }
    
    String linkURLText = display.getLink(point);
    if(linkURLText != null && !linkURLText.isEmpty() ) {
        // set the contents of the display to the contents of the url link
        loadPage(linkURLText);
        frame.repaint(); // is this necessary?
    }
  }

  protected void loadPage(String textInBar) {
    // TODO:  Replace this method with a method that loads text from a URL instead of a file.
    // This code here is just so that the simple browser will do something until you get the 
    // networking part working.
    

    List<String> contents = new ArrayList<String>();
    MyURL url = new MyURL(textInBar, currentURL);
    System.out.printf("loadpage: url: %s,  currentURL: %s,  textInBar: %s" +
        ",  url.path: %s\n", 
        url, currentURL, textInBar, url.path());
    try {
        WebTransactionClient wtc = new WebTransactionClient(url);

        // WebTransactionClient.getText() only returns a String,
        // so that string will be the only text in the display contents.
        String wtcText = wtc.getText();
        // Split the WebTransactionClient text into separate strings
        // for each newline character.
        String[] splitWTC = wtcText.split("\n");
        for(String s: splitWTC) {
            System.out.printf("\t:%s\n", s);
            contents.add(s);
        }
        //contents.add(wtc.getText());    
    } catch(IOException e) {
        //System.out.println("Cannot open file/WebTransactionClient");
        // TODO: add message to list, then settext
        contents.clear();
        contents.add(e.getMessage());
        display.setText(contents);
        e.printStackTrace();
    }

    /* Starter code 
     *
    File file = new File(textInBar);
    List<String> contents = null;
    try {

      // WARNING!! This code is missing a lot of important
      // checks ("does the file exist", "is it a text file", "is it readable", etc.)
      contents = Files.readAllLines(file.toPath(), Charset.defaultCharset());
    } catch (IOException e) {
      System.out.println("Can't open file " + file);
      e.printStackTrace();
    }
    */
    //update addressbar to show the new url
    //addressBar.setText(url.toString());
    //addressBar.setText(url.path());
    //addressBar.setText(textInBar);
    addressBar.setText(url.path());
    display.setText(contents);
    // after loading the page, update currentURL
    currentURL = url;
    System.out.printf("POST loadpage: url: %s\n\tcurrentURL: %s\n\ttextInBar: %s" +
        "\n\turl.path: %s\n", 
        url, currentURL, textInBar, url.path());
    //currentURL = new MyURL(url.toString(), currentURL);
    frame.repaint();
    //frame.pack();
    scrollPane.getVerticalScrollBar().setValue(scrollPane.getVerticalScrollBar().getMinimum());
  }

  // Fetch an image from from the server, or return null if 
  // the image isn't available.
  protected Image fetchImage(MyURL url) {
    // TODO:  implement me.
    // Hint:  Use a new WebTransactionClient object.
    try {
        WebTransactionClient wtc = new WebTransactionClient(url);
        return wtc.getImage();
    } catch(IOException e) {
        e.printStackTrace();
    }
    return null;
    //return null;
  }

  /**
   * Return the image at the given url.
   *
   * @param urlString the URL of the image to load.
   * @return The desired image, or {@code null} if the image isn't available.
   */
  public Image getCachedImage(String urlString) {
    MyURL url = new MyURL(urlString, currentURL);

    // This unusual syntax (the "new ImageCache.ImageLoader" stuff) is an "anonymous inner class.  It is Java's way
    // of allowing us to pass the fetchImage method as a parameter to the ImageCache.getImage.  You may have seen this 
    // syntax before with ActionListeners.  If not, I will be happy to explain it to you.
    return cache.getImage(url, new ImageCache.ImageLoader() {
      @Override
      public Image loadImage(MyURL url) {
        return fetchImage(url);
      }
    });
  }


  public static void main(String[] args) {

    // Notice that the display object (the StarterDisplay) is created *outside* of the 
    // SimpleBrowser object.  This is an example of "dependency injection" (also called 
    // "inversion of control").  In general, dependency injection simplifies unit testing.
    // I this case, I used dependency injection so that I could more easily write a subclass
    // of this browser that uses a completely different display class.
    String initial = args.length > 0 ? args[0]  : "sampleInput/starterSample.txt";
    new SimpleBrowser("CIS 371 Starter Browser", initial, new StarterDisplay());
  }


}
